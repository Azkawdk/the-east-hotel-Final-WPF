<?php

namespace App\Services;

use App\Models\Guest;
use App\Models\Payment;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ReservationService
{
    public function nights(string $in, string $out): int
    {
        return (int) Carbon::parse($in)->startOfDay()->diffInDays(Carbon::parse($out)->startOfDay());
    }

    /** Hitung biaya di backend. Nilai dari React tidak pernah dipercaya. */
    public function quote(float $pricePerNight, int $nights): array
    {
        $subtotal = round($pricePerNight * $nights);
        $service = round($subtotal * config('hotel.service_rate'));
        $tax = round(($subtotal + $service) * config('hotel.tax_rate'));

        return [
            'price_per_night' => $pricePerNight,
            'nights' => $nights,
            'subtotal' => $subtotal,
            'service_amount' => $service,
            'tax_amount' => $tax,
            'total_amount' => $subtotal + $service + $tax,
        ];
    }

    /**
     * ID kamar yang sudah terpakai pada rentang [in, out).
     * Dua rentang bertabrakan bila: existing.check_in < out  DAN  existing.check_out > in.
     * Check-out pada hari yang sama dengan check-in tamu lain TIDAK dianggap bentrok.
     */
    public function bookedRoomIds(string $in, string $out, bool $lock = false): array
    {
        $q = DB::table('reservation_details as d')
            ->join('reservations as r', 'r.id', '=', 'd.reservation_id')
            ->whereIn('r.status', Reservation::ACTIVE)
            ->where('r.check_in', '<', $out)
            ->where('r.check_out', '>', $in)
            ->where(function ($w) {
                // reservasi pending yang sudah lewat batas bayar tidak lagi menahan kamar
                $w->where('r.status', '!=', 'pending')->orWhere('r.expires_at', '>', now());
            });
        if ($lock) {
            $q->lockForUpdate();
        }

        return $q->pluck('d.room_id')->all();
    }

    /** @return \Illuminate\Support\Collection<int,int> room_type_id => jumlah kamar tersedia */
    public function availableCountByType(string $in, string $out)
    {
        return Room::where('status', 'available')
            ->whereNotIn('id', $this->bookedRoomIds($in, $out))
            ->selectRaw('room_type_id, COUNT(*) as total')
            ->groupBy('room_type_id')
            ->pluck('total', 'room_type_id');
    }

    /**
     * Membuat reservasi + detail + pembayaran awal dalam satu transaksi DB.
     *
     * Pencegahan double-booking: baris kamar bertipe sama dikunci (SELECT ... FOR UPDATE)
     * SEBAGAI PERNYATAAN PERTAMA di transaksi. Transaksi lain untuk tipe yang sama harus
     * menunggu sampai commit, lalu pengecekan bentrok dijalankan pada data terbaru.
     */
    public function create(array $data, ?int $userId = null): Reservation
    {
        return DB::transaction(function () use ($data, $userId) {
            $rooms = Room::where('room_type_id', $data['room_type_id'])
                ->where('status', 'available')
                ->orderBy('room_number')
                ->lockForUpdate()
                ->get();

            $type = RoomType::where('is_active', true)->find($data['room_type_id']);
            if (! $type) {
                throw ValidationException::withMessages(['room_type_id' => 'Tipe kamar tidak tersedia.']);
            }
            if ($data['guests_count'] > $type->capacity) {
                throw ValidationException::withMessages(['guests_count' => "Kapasitas maksimal tipe ini {$type->capacity} tamu."]);
            }

            $booked = $this->bookedRoomIds($data['check_in'], $data['check_out'], true);
            $free = $rooms->reject(fn ($r) => in_array($r->id, $booked));
            $room = isset($data['room_id']) ? $free->firstWhere('id', (int) $data['room_id']) : $free->first();
            if (! $room) {
                throw ValidationException::withMessages(['room_type_id' => 'Maaf, kamar untuk tanggal tersebut baru saja terisi. Silakan pilih tanggal atau tipe lain.']);
            }

            $nights = $this->nights($data['check_in'], $data['check_out']);
            $q = $this->quote($type->base_price, $nights);

            $guest = Guest::withTrashed()->firstOrNew(['email' => strtolower($data['guest']['email'])]);
            $guest->fill([
                'full_name' => $data['guest']['full_name'],
                'phone' => $data['guest']['phone'],
                'country' => $data['guest']['country'] ?? $guest->country,
            ]);
            $guest->deleted_at = null;
            $guest->save();

            $atHotel = ($data['payment_method'] ?? 'simulation') === 'pay_at_hotel';

            $reservation = Reservation::create([
                'code' => $this->newCode(),
                'guest_id' => $guest->id,
                'check_in' => $data['check_in'],
                'check_out' => $data['check_out'],
                'guests_count' => $data['guests_count'],
                'nights' => $nights,
                'subtotal' => $q['subtotal'],
                'service_amount' => $q['service_amount'],
                'tax_amount' => $q['tax_amount'],
                'total_amount' => $q['total_amount'],
                'status' => $atHotel ? 'confirmed' : 'pending',
                'special_requests' => $data['special_requests'] ?? null,
                'expires_at' => $atHotel ? null : now()->addMinutes(config('hotel.payment_expiry_minutes')),
                'created_by' => $userId,
            ]);

            $reservation->details()->create([
                'room_id' => $room->id,
                'room_type_id' => $type->id,
                'price_per_night' => $type->base_price, // snapshot
                'nights' => $nights,
                'subtotal' => $q['subtotal'],
            ]);

            $reservation->payment()->create([
                'amount' => $q['total_amount'],
                'method' => $data['payment_method'] ?? 'simulation',
                'status' => 'pending',
                'processed_by' => $userId,
            ]);

            return $this->load($reservation);
        }, 3);
    }

    public function load(Reservation $r): Reservation
    {
        return $r->load(['guest', 'details.room', 'details.roomType', 'payment']);
    }

    public function newCode(): string
    {
        do {
            $code = 'TEH-'.strtoupper(Str::random(6));
        } while (Reservation::where('code', $code)->exists());

        return $code;
    }

    /** Ubah status reservasi sesuai tabel transisi. */
    public function changeStatus(Reservation $r, string $to, ?string $reason = null): Reservation
    {
        if (! in_array($to, Reservation::TRANSITIONS[$r->status] ?? [], true)) {
            throw ValidationException::withMessages(['status' => "Status '{$r->status}' tidak dapat diubah menjadi '{$to}'."]);
        }

        return DB::transaction(function () use ($r, $to, $reason) {
            $r->status = $to;
            if ($to === 'cancelled') {
                $r->cancelled_at = now();
                $r->cancel_reason = $reason ?: 'Dibatalkan';
                $r->expires_at = null;
                // pembayaran yang belum dibayar ikut gagal; yang sudah dibayar diproses manual (refund)
                $r->payment()->where('status', 'pending')->update(['status' => 'failed']);
            }
            $r->save();

            return $this->load($r);
        });
    }

    /** Ubah status pembayaran sesuai tabel transisi; pembayaran lunas mengonfirmasi reservasi pending. */
    public function changePaymentStatus(Payment $p, string $to, ?int $userId = null, ?string $notes = null): Payment
    {
        if (! in_array($to, Payment::TRANSITIONS[$p->status] ?? [], true)) {
            throw ValidationException::withMessages(['status' => "Pembayaran '{$p->status}' tidak dapat diubah menjadi '{$to}'."]);
        }

        return DB::transaction(function () use ($p, $to, $userId, $notes) {
            $p->status = $to;
            $p->processed_by = $userId ?? $p->processed_by;
            if ($notes) {
                $p->notes = $notes;
            }
            if ($to === 'paid') {
                $p->paid_at = now();
                $p->reference = $p->reference ?: strtoupper(substr($p->method, 0, 3)).'-'.strtoupper(Str::random(8));
                $r = $p->reservation;
                if ($r->status === 'pending') {
                    $r->update(['status' => 'confirmed', 'expires_at' => null]);
                }
            }
            $p->save();

            return $p->fresh();
        });
    }

    public function expirePending(): int
    {
        $n = 0;
        Reservation::where('status', 'pending')->where('expires_at', '<', now())->get()->each(function ($r) use (&$n) {
            $this->changeStatus($r, 'cancelled', 'Batas waktu pembayaran habis');
            $n++;
        });

        return $n;
    }
}
