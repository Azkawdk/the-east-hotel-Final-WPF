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
        $q = Room::with('roomType')->orderBy('room_number');
        if ($request->filled('room_type_id')) {
            $q->where('room_type_id', $request->room_type_id);
        }
        if ($request->filled('status')) {
            $q->where('status', $request->status);
        }

        $rooms     = $q->paginate(20)->withQueryString();
        $roomTypes = RoomType::orderBy('name')->get();
        $statuses  = ['available', 'maintenance', 'out_of_service'];

        return view('admin.rooms.index', compact('rooms', 'roomTypes', 'statuses'));
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
