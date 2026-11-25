<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AdminRoomController extends Controller
{
    public function index(Request $request)
    {
        $today = today()->toDateString();

        // Cari kamar yang sedang aktif terisi/terpesan hari ini
        // Sertakan 'pending' agar kamar yang baru dipesan (belum bayar) tetap tampil "Dipesan"
        $activeReservations = \Illuminate\Support\Facades\DB::table('reservation_details as d')
            ->join('reservations as r', 'r.id', '=', 'd.reservation_id')
            ->join('guests as g', 'g.id', '=', 'r.guest_id')
            ->whereIn('r.status', ['pending', 'confirmed', 'checked_in'])
            ->where('r.check_in', '<=', $today)
            ->where('r.check_out', '>', $today)
            ->select('d.room_id', 'r.id as reservation_id', 'r.code', 'r.status as res_status', 'g.full_name as guest_name')
            ->get()
            ->keyBy('room_id');

        $bookedRoomIds = $activeReservations->keys()->all();

        $q = Room::with('roomType')->orderBy('room_number');

        if ($request->filled('room_type_id')) {
            $q->where('room_type_id', $request->room_type_id);
        }

        if ($request->filled('status')) {
            if ($request->status === 'occupied') {
                // Terisi = kamar yang ada di reservasi aktif hari ini (pending/confirmed/checked_in)
                $q->whereIn('id', $bookedRoomIds);
            } elseif ($request->status === 'available') {
                // Tersedia = status available DAN tidak ada reservasi aktif hari ini
                $q->where('status', 'available')->whereNotIn('id', $bookedRoomIds);
            } else {
                $q->where('status', $request->status);
            }
        }

        $rooms     = $q->paginate(20)->withQueryString();
        $roomTypes = RoomType::orderBy('name')->get();
        $statuses  = ['available', 'occupied', 'maintenance', 'out_of_service'];

        return view('admin.rooms.index', compact('rooms', 'roomTypes', 'statuses', 'activeReservations'));
    }

    public function create()
    {
        $roomTypes = RoomType::where('is_active', true)->orderBy('name')->get();
        return view('admin.rooms.create', compact('roomTypes'));
    }

    public function store(Request $request)
    {
        $d = $request->validate([
            'room_type_id' => 'required|integer|exists:room_types,id',
            'room_number'  => 'required|string|max:10|unique:rooms,room_number',
            'floor'        => 'required|integer|min:0|max:50',
            'status'       => 'required|in:available,maintenance,out_of_service',
            'notes'        => 'nullable|string|max:255',
        ]);

        Room::create($d);
        return redirect()->route('admin.rooms.index')->with('success', "Kamar {$d['room_number']} berhasil ditambahkan.");
    }

    public function edit(Room $room)
    {
        $roomTypes = RoomType::where('is_active', true)->orderBy('name')->get();
        return view('admin.rooms.edit', compact('room', 'roomTypes'));
    }

    public function update(Request $request, Room $room)
    {
        $d = $request->validate([
            'room_type_id' => 'required|integer|exists:room_types,id',
            'room_number'  => ['required', 'string', 'max:10', Rule::unique('rooms', 'room_number')->ignore($room->id)],
            'floor'        => 'required|integer|min:0|max:50',
            'status'       => 'required|in:available,maintenance,out_of_service',
            'notes'        => 'nullable|string|max:255',
        ]);

        $room->update($d);
        return redirect()->route('admin.rooms.index')->with('success', "Kamar {$room->room_number} berhasil diperbarui.");
    }

    public function destroy(Room $room)
    {
        $busy = Reservation::whereIn('status', Reservation::ACTIVE)->whereDate('check_out', '>', today())
            ->whereHas('details', fn ($q) => $q->where('room_id', $room->id))->exists();

        if ($busy) {
            return back()->withErrors(['room' => 'Kamar masih terhubung ke reservasi aktif.']);
        }

        $no = $room->room_number;
        $room->delete();
        return redirect()->route('admin.rooms.index')->with('success', "Kamar $no berhasil dihapus.");
    }
}
