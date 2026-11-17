<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Reservation;
use App\Models\ReservationDetail;
use App\Models\Room;

class DashboardController extends Controller
{
    public function index()
    {
        $today = today()->toDateString();
        $occupied = ReservationDetail::whereHas('reservation', fn ($q) => $q
            ->whereIn('status', ['confirmed', 'checked_in'])
            ->where('check_in', '<=', $today)->where('check_out', '>', $today))->count();
        $operational = Room::where('status', 'available')->count();

        return [
            'total_reservations' => Reservation::count(),
            'pending_reservations' => Reservation::where('status', 'pending')->count(),
            'checkins_today' => Reservation::whereIn('status', ['confirmed', 'pending'])->where('check_in', $today)->count(),
            'checkouts_today' => Reservation::where('status', 'checked_in')->where('check_out', $today)->count(),
            'rooms_operational' => $operational,
            'rooms_occupied_today' => $occupied,
            'rooms_available_today' => max(0, $operational - $occupied),
            'revenue_this_month' => (float) Payment::where('status', 'paid')
                ->whereBetween('paid_at', [now()->startOfMonth(), now()->endOfMonth()])->sum('amount'),
            'payments_pending' => Payment::where('status', 'pending')->count(),
            'recent_reservations' => Reservation::with(['guest', 'payment', 'details.room'])->latest()->limit(6)->get(),
        ];
    }
}
