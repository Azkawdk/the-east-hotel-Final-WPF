<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guest;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class GuestController extends Controller
{
    public function index(Request $request)
    {
        $q = Guest::withCount('reservations')->orderBy('full_name');
        if ($s = $request->query('q')) {
            $q->where(fn ($w) => $w->where('full_name', 'like', "%$s%")->orWhere('email', 'like', "%$s%")->orWhere('phone', 'like', "%$s%"));
        }

        return $q->paginate(15);
    }

    public function show(Guest $guest)
    {
        return $guest->load(['reservations' => fn ($q) => $q->latest()->with('payment')]);
    }

    public function update(Request $request, Guest $guest)
    {
        $d = $request->validate([
            'full_name' => 'required|string|max:120',
            'email' => ['required', 'email', 'max:150', Rule::unique('guests', 'email')->ignore($guest->id)],
            'phone' => 'required|string|max:30',
            'country' => 'nullable|string|max:80',
            'id_number' => 'nullable|string|max:50',
        ]);
        $guest->update($d);

        return $guest;
    }

    public function destroy(Guest $guest)
    {
        if ($guest->reservations()->whereIn('status', Reservation::ACTIVE)->exists()) {
            throw ValidationException::withMessages(['guest' => 'Tamu masih memiliki reservasi aktif.']);
        }
        $guest->delete(); // soft delete: riwayat transaksi tetap utuh

        return response()->noContent();
    }
}
