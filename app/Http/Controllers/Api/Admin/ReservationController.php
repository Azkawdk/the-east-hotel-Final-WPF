<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Services\ReservationService;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function __construct(private ReservationService $svc)
    {
    }

    public function index(Request $request)
    {
        $q = Reservation::with(['guest', 'details.room.roomType', 'payment'])->latest();

        if ($s = $request->query('q')) {
            $q->where(fn ($w) => $w->where('code', 'like', "%$s%")
                ->orWhereHas('guest', fn ($g) => $g->where('full_name', 'like', "%$s%")->orWhere('email', 'like', "%$s%")));
        }
        if ($request->filled('status')) {
            $q->where('status', $request->status);
        }
        if ($request->filled('payment_status')) {
            $q->whereHas('payment', fn ($p) => $p->where('status', $request->payment_status));
        }
        if ($request->filled('room')) {
            $q->whereHas('details.room', fn ($r) => $r->where('room_number', 'like', '%'.$request->room.'%'));
        }
        if ($request->filled('date_from')) {
            $q->where('check_in', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $q->where('check_in', '<=', $request->date_to);
        }

        return $q->paginate(15);
    }

    public function show(Reservation $reservation)
    {
        return $this->svc->load($reservation);
    }

    /** Reservasi manual oleh staf/admin (mis. tamu walk-in atau telepon). */
    public function store(Request $request)
    {
        $data = $request->validate([
            'room_type_id' => 'required|integer|exists:room_types,id',
            'room_id' => 'nullable|integer|exists:rooms,id',
            'check_in' => 'required|date_format:Y-m-d',
            'check_out' => 'required|date_format:Y-m-d|after:check_in',
            'guests_count' => 'required|integer|min:1|max:10',
            'guest.full_name' => 'required|string|max:120',
            'guest.email' => 'required|email|max:150',
            'guest.phone' => 'required|string|max:30',
            'special_requests' => 'nullable|string|max:500',
            'payment_method' => 'required|in:pay_at_hotel,cash,bank_transfer,simulation',
        ]);

        return response()->json($this->svc->create($data, $request->user()->id), 201);
    }

    public function updateStatus(Request $request, Reservation $reservation)
    {
        $d = $request->validate([
            'status' => 'required|in:pending,confirmed,checked_in,checked_out,cancelled',
            'reason' => 'nullable|string|max:255',
        ]);

        return $this->svc->changeStatus($reservation, $d['status'], $d['reason'] ?? null);
    }
}
