<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use App\Models\Payment;
use App\Models\Reservation;
use App\Models\RoomType;
use App\Services\ReservationService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PublicController extends Controller
{
    public function __construct(private ReservationService $svc)
    {
    }

    private function dateRules(): array
    {
        return [
            'check_in' => 'required|date_format:Y-m-d|after_or_equal:today',
            'check_out' => 'required|date_format:Y-m-d|after:check_in',
            'guests' => 'required|integer|min:1|max:10',
        ];
    }

    private function assertMaxNights(string $in, string $out): void
    {
        if ($this->svc->nights($in, $out) > config('hotel.max_nights')) {
            throw ValidationException::withMessages(['check_out' => 'Maksimal menginap '.config('hotel.max_nights').' malam.']);
        }
    }

    /** Daftar tipe kamar; bila tanggal & tamu diberikan, hanya yang tersedia + sisa kamar + estimasi biaya. */
    public function roomTypes(Request $request)
    {
        $q = RoomType::with('amenities')->where('is_active', true)->orderBy('base_price');
        if ($request->filled('price_min')) {
            $q->where('base_price', '>=', (float) $request->price_min);
        }
        if ($request->filled('price_max')) {
            $q->where('base_price', '<=', (float) $request->price_max);
        }

        $searching = $request->filled('check_in') || $request->filled('check_out');
        if (! $searching) {
            return $q->get();
        }

        $d = $request->validate($this->dateRules());
        $this->assertMaxNights($d['check_in'], $d['check_out']);
        $counts = $this->svc->availableCountByType($d['check_in'], $d['check_out']);

        return $q->where('capacity', '>=', $d['guests'])->get()
            ->filter(fn ($t) => ($counts[$t->id] ?? 0) > 0)
            ->map(function ($t) use ($counts, $d) {
                $t->setAttribute('available_rooms', (int) $counts[$t->id]);
                $t->setAttribute('quote', $this->svc->quote($t->base_price, $this->svc->nights($d['check_in'], $d['check_out'])));

                return $t;
            })->values();
    }

    public function roomType(Request $request, string $slug)
    {
        $t = RoomType::with('amenities')->where('is_active', true)->where('slug', $slug)->firstOrFail();
        if ($request->filled('check_in')) {
            $d = $request->validate($this->dateRules());
            $this->assertMaxNights($d['check_in'], $d['check_out']);
            $counts = $this->svc->availableCountByType($d['check_in'], $d['check_out']);
            $t->setAttribute('available_rooms', (int) ($counts[$t->id] ?? 0));
            $t->setAttribute('quote', $this->svc->quote($t->base_price, $this->svc->nights($d['check_in'], $d['check_out'])));
        }

        return $t;
    }

    public function amenities()
    {
        return Amenity::orderBy('name')->get();
    }

    public function storeReservation(Request $request)
    {
        $data = $request->validate([
            'room_type_id' => 'required|integer|exists:room_types,id',
            'check_in' => 'required|date_format:Y-m-d|after_or_equal:today',
            'check_out' => 'required|date_format:Y-m-d|after:check_in',
            'guests_count' => 'required|integer|min:1|max:10',
            'guest.full_name' => 'required|string|max:120',
            'guest.email' => 'required|email|max:150',
            'guest.phone' => 'required|string|min:6|max:30',
            'guest.country' => 'nullable|string|max:80',
            'special_requests' => 'nullable|string|max:500',
            'payment_method' => 'required|in:simulation,pay_at_hotel',
        ]);
        $this->assertMaxNights($data['check_in'], $data['check_out']);

        $reservation = $this->svc->create($data);

        return response()->json($reservation, 201);
    }

    public function lookup(Request $request)
    {
        return $this->findByCodeAndEmail($request->validate(['code' => 'required|string|max:12', 'email' => 'required|email']));
    }

    private function findByCodeAndEmail(array $d): Reservation
    {
        $r = Reservation::with(['guest', 'details.room', 'details.roomType', 'payment'])
            ->where('code', strtoupper($d['code']))
            ->whereHas('guest', fn ($g) => $g->whereRaw('LOWER(email) = ?', [strtolower($d['email'])]))
            ->first();
        abort_if(! $r, 404, 'Reservasi tidak ditemukan. Periksa kode dan email Anda.');

        return $r;
    }

    /** Pembayaran tamu: simulasi (berhasil/gagal) atau bayar saat check-in. Tidak ada payment gateway sungguhan. */
    public function pay(Request $request, string $code)
    {
        $d = $request->validate([
            'email' => 'required|email',
            'method' => 'required|in:simulation,pay_at_hotel',
            'outcome' => 'nullable|in:success,fail',
        ]);
        $r = $this->findByCodeAndEmail(['code' => $code, 'email' => $d['email']]);
        $p = $r->payment;

        if (! in_array($p->status, ['pending', 'failed'], true) || ! in_array($r->status, ['pending', 'confirmed'], true)) {
            throw ValidationException::withMessages(['method' => 'Reservasi ini tidak dapat dibayar lagi.']);
        }
        if ($r->status === 'pending' && $r->expires_at && $r->expires_at->isPast()) {
            throw ValidationException::withMessages(['method' => 'Batas waktu pembayaran telah habis. Silakan buat reservasi baru.']);
        }

        $p->method = $d['method'];
        if ($p->status === 'failed') {
            $p->status = 'pending';
        }
        $p->save();

        if ($d['method'] === 'pay_at_hotel') {
            $r->update(['status' => 'confirmed', 'expires_at' => null]);
        } elseif (($d['outcome'] ?? 'success') === 'success') {
            $this->svc->changePaymentStatus($p, 'paid');
        } else {
            $this->svc->changePaymentStatus($p, 'failed', null, 'Simulasi pembayaran gagal');
        }

        return $this->svc->load($r->fresh());
    }
}
