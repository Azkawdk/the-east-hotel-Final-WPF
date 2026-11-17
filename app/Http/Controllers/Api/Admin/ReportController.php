<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function summary(Request $request)
    {
        $d = $request->validate(['from' => 'nullable|date_format:Y-m-d', 'to' => 'nullable|date_format:Y-m-d|after_or_equal:from']);
        $from = $d['from'] ?? now()->startOfMonth()->toDateString();
        $to = $d['to'] ?? now()->toDateString();

        $byStatus = Reservation::whereDate('created_at', '>=', $from)->whereDate('created_at', '<=', $to)
            ->selectRaw('status, COUNT(*) as total')->groupBy('status')->pluck('total', 'status');

        $daily = Payment::where('status', 'paid')->whereDate('paid_at', '>=', $from)->whereDate('paid_at', '<=', $to)
            ->selectRaw('DATE(paid_at) as date, COUNT(*) as payments, SUM(amount) as revenue')
            ->groupBy('date')->orderBy('date')->get();

        $byType = DB::table('reservation_details as d')
            ->join('reservations as r', 'r.id', '=', 'd.reservation_id')
            ->join('room_types as t', 't.id', '=', 'd.room_type_id')
            ->where('r.status', '!=', 'cancelled')
            ->whereDate('r.created_at', '>=', $from)->whereDate('r.created_at', '<=', $to)
            ->selectRaw('t.name, COUNT(*) as bookings, SUM(d.nights) as nights, SUM(d.subtotal) as room_revenue')
            ->groupBy('t.name')->get();

        return [
            'from' => $from,
            'to' => $to,
            'reservations_total' => $byStatus->sum(),
            'reservations_by_status' => $byStatus,
            'revenue_total' => (float) $daily->sum('revenue'),
            'daily_revenue' => $daily,
            'by_room_type' => $byType,
        ];
    }
}
