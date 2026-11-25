<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Reservation;
use App\Models\ReservationDetail;
use App\Models\Room;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $today = today()->toDateString();

        // Hitung kamar yang terisi hari ini (pending + confirmed + checked_in)
        $occupiedRoomIds = ReservationDetail::whereHas('reservation', fn ($q) => $q
            ->whereIn('status', ['pending', 'confirmed', 'checked_in'])
            ->where('check_in', '<=', $today)->where('check_out', '>', $today))
            ->pluck('room_id')
            ->unique()
            ->count();

        $totalOperational = Room::where('status', 'available')->count();

        $stats = [
            'total_reservations'    => Reservation::count(),
            'pending_reservations'  => Reservation::where('status', 'pending')->count(),
            'checkins_today'        => Reservation::whereIn('status', ['confirmed', 'pending'])->where('check_in', $today)->count(),
            'checkouts_today'       => Reservation::where('status', 'checked_in')->where('check_out', $today)->count(),
            'rooms_operational'     => $totalOperational,
            'rooms_occupied_today'  => $occupiedRoomIds,
            'rooms_available_today' => max(0, $totalOperational - $occupiedRoomIds),
            'revenue_this_month'    => (float) Payment::where('status', 'paid')
                ->whereBetween('paid_at', [now()->startOfMonth(), now()->endOfMonth()])->sum('amount'),
            'payments_pending'      => Payment::where('status', 'pending')->count(),
        ];

        $recent = Reservation::with(['guest', 'payment', 'details.room'])->latest()->limit(8)->get();

        return view('admin.dashboard', compact('stats', 'recent'));
    }
}
