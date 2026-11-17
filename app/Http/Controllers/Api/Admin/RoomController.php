<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class RoomController extends Controller
{
    private function rules(?int $id = null): array
    {
        return [
            'room_type_id' => 'required|integer|exists:room_types,id',
            'room_number' => ['required', 'string', 'max:10', Rule::unique('rooms', 'room_number')->ignore($id)],
            'floor' => 'required|integer|min:0|max:50',
            'status' => 'required|in:available,maintenance,out_of_service',
            'notes' => 'nullable|string|max:255',
        ];
    }

    public function index(Request $request)
    {
        $q = Room::with('roomType')->orderBy('room_number');
        foreach (['room_type_id', 'status'] as $f) {
            if ($request->filled($f)) {
                $q->where($f, $request->$f);
            }
        }

        return $q->get();
    }

    public function store(Request $request)
    {
        return response()->json(Room::create($request->validate($this->rules()))->load('roomType'), 201);
    }

    public function update(Request $request, Room $room)
    {
        $room->update($request->validate($this->rules($room->id)));

        return $room->load('roomType');
    }

    public function destroy(Room $room)
    {
        $busy = Reservation::whereIn('status', Reservation::ACTIVE)->whereDate('check_out', '>', today())
            ->whereHas('details', fn ($q) => $q->where('room_id', $room->id))->exists();
        if ($busy) {
            throw ValidationException::withMessages(['room' => 'Kamar masih terhubung ke reservasi aktif.']);
        }
        $room->delete();

        return response()->noContent();
    }
}
