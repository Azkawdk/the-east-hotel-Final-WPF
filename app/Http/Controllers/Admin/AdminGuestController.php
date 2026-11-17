<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guest;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminGuestController extends Controller
{
    public function index(Request $request)
    {
        $q = Guest::withCount('reservations')->orderBy('full_name');
        if ($s = $request->query('q')) {
            $q->where(fn ($w) => $w->where('full_name', 'like', "%$s%")
                ->orWhere('email', 'like', "%$s%")->orWhere('phone', 'like', "%$s%"));
        }
        $guests = $q->paginate(15)->withQueryString();
        return view('admin.guests.index', compact('guests'));
    }

    public function show(Guest $guest)
    {
        $guest->load(['reservations' => fn ($q) => $q->latest()->with('payment')]);
        return view('admin.guests.show', compact('guest'));
    }

    public function edit(Guest $guest)
    {
        return view('admin.guests.edit', compact('guest'));
    }

    public function update(Request $request, Guest $guest)
    {
        $d = $request->validate([
            'full_name' => 'required|string|max:120',
            'email'     => ['required', 'email', 'max:150', Rule::unique('guests', 'email')->ignore($guest->id)],
            'phone'     => 'required|string|max:30',
            'country'   => 'nullable|string|max:80',
            'id_number' => 'nullable|string|max:50',
        ]);

        $guest->update($d);
        return redirect()->route('admin.guests.show', $guest)->with('success', 'Data tamu berhasil diperbarui.');
    }

    public function destroy(Guest $guest)
    {
        if ($guest->reservations()->whereIn('status', Reservation::ACTIVE)->exists()) {
            return back()->withErrors(['guest' => 'Tamu masih memiliki reservasi aktif.']);
        }
        $name = $guest->full_name;
        $guest->delete();
        return redirect()->route('admin.guests.index')->with('success', "Tamu '$name' berhasil dihapus.");
    }
}
