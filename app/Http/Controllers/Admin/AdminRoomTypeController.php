<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AdminRoomTypeController extends Controller
{
    public function index()
    {
        $roomTypes = RoomType::with('amenities')->withCount('rooms')->orderBy('base_price')->get();
        return view('admin.room-types.index', compact('roomTypes'));
    }

    public function create()
    {
        $amenities = Amenity::where('scope', 'room')->orderBy('name')->get();
        return view('admin.room-types.create', compact('amenities'));
    }

    public function store(Request $request)
    {
        $d = $request->validate([
            'name'        => 'required|string|max:100',
            'description' => 'required|string|max:2000',
            'capacity'    => 'required|integer|min:1|max:10',
            'base_price'  => 'required|numeric|min:0',
            'size_m2'     => 'nullable|integer|min:1|max:1000',
            'bed_type'    => 'nullable|string|max:60',
            'image_url'   => 'nullable|url|max:255',
            'is_active'   => 'boolean',
            'amenity_ids' => 'array',
            'amenity_ids.*'=> 'integer|exists:amenities,id',
        ]);

        $d['slug']      = $this->uniqueSlug($d['name']);
        $d['is_active'] = $request->boolean('is_active', true);

        $type = RoomType::create($d);
        $type->amenities()->sync($d['amenity_ids'] ?? []);

        return redirect()->route('admin.room-types.index')->with('success', "Tipe kamar '{$type->name}' berhasil ditambahkan.");
    }

    public function edit(RoomType $roomType)
    {
        $amenities         = Amenity::where('scope', 'room')->orderBy('name')->get();
        $selectedAmenities = $roomType->amenities->pluck('id')->toArray();
        return view('admin.room-types.edit', compact('roomType', 'amenities', 'selectedAmenities'));
    }

    public function update(Request $request, RoomType $roomType)
    {
        $d = $request->validate([
            'name'        => 'required|string|max:100',
            'description' => 'required|string|max:2000',
            'capacity'    => 'required|integer|min:1|max:10',
            'base_price'  => 'required|numeric|min:0',
            'size_m2'     => 'nullable|integer|min:1|max:1000',
            'bed_type'    => 'nullable|string|max:60',
            'image_url'   => 'nullable|url|max:255',
            'is_active'   => 'boolean',
            'amenity_ids' => 'array',
            'amenity_ids.*'=> 'integer|exists:amenities,id',
        ]);

        $d['is_active'] = $request->boolean('is_active', false);
        $roomType->update($d);
        $roomType->amenities()->sync($d['amenity_ids'] ?? []);

        return redirect()->route('admin.room-types.index')->with('success', "Tipe kamar '{$roomType->name}' berhasil diperbarui.");
    }

    public function destroy(RoomType $roomType)
    {
        $busy = \App\Models\Reservation::whereIn('status', \App\Models\Reservation::ACTIVE)
            ->whereDate('check_out', '>', today())
            ->whereHas('details', fn ($q) => $q->where('room_type_id', $roomType->id))->exists();

        if ($busy) {
            return back()->withErrors(['room_type' => 'Masih ada reservasi aktif pada tipe kamar ini.']);
        }

        $name = $roomType->name;
        $roomType->delete();
        return redirect()->route('admin.room-types.index')->with('success', "Tipe kamar '$name' berhasil dihapus.");
    }

    private function uniqueSlug(string $name): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i    = 2;
        while (RoomType::withTrashed()->where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }
}
