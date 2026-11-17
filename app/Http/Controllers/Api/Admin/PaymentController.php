<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\ReservationService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(private ReservationService $svc)
    {
    }

    public function index(Request $request)
    {
        $q = Payment::with('reservation.guest')->latest();
        foreach (['status', 'method'] as $f) {
            if ($request->filled($f)) {
                $q->where($f, $request->$f);
            }
        }
        if ($s = $request->query('q')) {
            $q->whereHas('reservation', fn ($r) => $r->where('code', 'like', "%$s%")
                ->orWhereHas('guest', fn ($g) => $g->where('full_name', 'like', "%$s%")));
        }

        return $q->paginate(15);
    }

    public function update(Request $request, Payment $payment)
    {
        $d = $request->validate([
            'status' => 'required|in:pending,paid,failed,refunded',
            'method' => 'nullable|in:simulation,pay_at_hotel,cash,bank_transfer',
            'notes' => 'nullable|string|max:255',
        ]);
        if (! empty($d['method'])) {
            $payment->update(['method' => $d['method']]);
        }

        return $this->svc->changePaymentStatus($payment, $d['status'], $request->user()->id, $d['notes'] ?? null)
            ->load('reservation.guest');
    }
}
