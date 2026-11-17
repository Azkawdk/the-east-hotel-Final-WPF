<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class RoomTypeController extends Controller
{
    private function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'description' => 'required|string|max:2000',
            'capacity' => 'required|integer|min:1|max:10',
            'base_price' => 'required|numeric|min:0|max:999999999',
            'size_m2' => 'nullable|integer|min:1|max:1000',
            'bed_type' => 'nullable|string|max:60',
            'image_url' => 'nullable|url|max:255',
            'is_active' => 'boolean',
            'amenity_ids' => 'array',
            'amenity_ids.*' => 'integer|exists:amenities,id',
        ];
    }

    public function index()
    {
        return RoomType::with('amenities')->withCount('rooms')->orderBy('base_price')->get();
    }

    public function store(Request $request)
    {
        $d = $request->validate($this->rules());
        $d['slug'] = $this->uniqueSlug($d['name']);
        $type = RoomType::create($d);
        $type->amenities()->sync($d['amenity_ids'] ?? []);

        return response()->json($type->load('amenities'), 201);
    }

    public function update(Request $request, RoomType $room_type)
    {
        $d = $request->validate($this->rules());
        $room_type->update($d);
        $room_type->amenities()->sync($d['amenity_ids'] ?? []);

        return $room_type->load('amenities');
    }

    public function destroy(RoomType $room_type)
    {
        $busy = Reservation::whereIn('status', Reservation::ACTIVE)
            ->whereDate('check_out', '>', today())
            ->whereHas('details', fn ($q) => $q->where('room_type_id', $room_type->id))->exists();
        if ($busy) {
            throw ValidationException::withMessages(['room_type' => 'Masih ada reservasi aktif pada tipe kamar ini.']);
        }
        $room_type->delete(); // soft delete; detail reservasi lama tetap valid

        return response()->noContent();
    }

    private function uniqueSlug(string $name): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i = 2;
        while (RoomType::withTrashed()->where('slug', $slug)->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }
}
