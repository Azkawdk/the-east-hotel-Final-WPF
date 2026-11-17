<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\ReservationService;
use Illuminate\Http\Request;

class AdminPaymentController extends Controller
{
    public function __construct(private ReservationService $svc) {}

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
        $payments = $q->paginate(15)->withQueryString();
        $statuses = ['pending', 'paid', 'failed', 'refunded'];
        $methods  = ['simulation', 'pay_at_hotel', 'cash', 'bank_transfer'];
        return view('admin.payments.index', compact('payments', 'statuses', 'methods'));
    }

    public function update(Request $request, Payment $payment)
    {
        $d = $request->validate([
            'status' => 'required|in:pending,paid,failed,refunded',
            'method' => 'nullable|in:simulation,pay_at_hotel,cash,bank_transfer',
            'notes'  => 'nullable|string|max:255',
        ]);

        if (!empty($d['method'])) {
            $payment->update(['method' => $d['method']]);
        }

        try {
            $this->svc->changePaymentStatus($payment, $d['status'], auth()->id(), $d['notes'] ?? null);
            return back()->with('success', 'Status pembayaran berhasil diperbarui.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors());
        }
    }
}
