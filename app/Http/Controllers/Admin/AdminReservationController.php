<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\RoomType;
use App\Services\ReservationService;
use Illuminate\Http\Request;

class AdminReservationController extends Controller
{
    public function __construct(private ReservationService $svc) {}

    public function index(Request $request)
    {
        $q = Reservation::with(['guest', 'details.room.roomType', 'payment'])->latest();

        if ($s = $request->query('q')) {
            $q->where(fn ($w) => $w->where('code', 'like', "%$s%")
                ->orWhereHas('guest', fn ($g) => $g->where('full_name', 'like', "%$s%")
                    ->orWhere('email', 'like', "%$s%")));
        }
        if ($request->filled('status')) {
            $q->where('status', $request->status);
        }
        if ($request->filled('date_from')) {
            $q->where('check_in', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $q->where('check_in', '<=', $request->date_to);
        }

        $reservations = $q->paginate(15)->withQueryString();
        $statuses = ['pending', 'confirmed', 'checked_in', 'checked_out', 'cancelled'];

        return view('admin.reservations.index', compact('reservations', 'statuses'));
    }

    public function show(Reservation $reservation)
    {
        $reservation = $this->svc->load($reservation);
        return view('admin.reservations.show', compact('reservation'));
    }

    public function create()
    {
        $roomTypes = RoomType::where('is_active', true)->orderBy('base_price')->get();
        return view('admin.reservations.create', compact('roomTypes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'room_type_id'    => 'required|integer|exists:room_types,id',
            'check_in'        => 'required|date_format:Y-m-d|after_or_equal:today',
            'check_out'       => 'required|date_format:Y-m-d|after:check_in',
            'guests_count'    => 'required|integer|min:1|max:10',
            'guest.full_name' => 'required|string|max:120',
            'guest.email'     => 'required|email|max:150',
            'guest.phone'     => 'required|string|max:30',
            'guest.country'   => 'nullable|string|max:80',
            'special_requests'=> 'nullable|string|max:500',
            'payment_method'  => 'required|in:pay_at_hotel,cash,bank_transfer,simulation',
        ]);

        try {
            $reservation = $this->svc->create($data, auth()->id());
            return redirect()->route('admin.reservations.show', $reservation)
                ->with('success', "Reservasi {$reservation->code} berhasil dibuat.");
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }
    }

    public function updateStatus(Request $request, Reservation $reservation)
    {
        $d = $request->validate([
            'status' => 'required|in:pending,confirmed,checked_in,checked_out,cancelled',
            'reason' => 'nullable|string|max:255',
        ]);

        try {
            $this->svc->changeStatus($reservation, $d['status'], $d['reason'] ?? null);
            return back()->with('success', 'Status reservasi berhasil diperbarui.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors());
        }
    }

    public function destroy(Reservation $reservation)
    {
        $code = $reservation->code;
        \Illuminate\Support\Facades\DB::transaction(function () use ($reservation) {
            $reservation->details()->delete();
            $reservation->payment()->delete();
            $reservation->delete();
        });

        return redirect()->route('admin.reservations.index')
            ->with('success', "Reservasi {$code} berhasil dihapus.");
    }
}
