<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use Illuminate\Http\Request;

class AdminAmenityController extends Controller
{
    public function index()
    {
        $amenities = Amenity::orderBy('scope')->orderBy('name')->get();
        return view('admin.amenities.index', compact('amenities'));
    }

    public function create()
    {
        return view('admin.amenities.create');
    }

    public function store(Request $request)
    {
        $d = $request->validate([
            'name'        => 'required|string|max:100',
            'description' => 'nullable|string|max:255',
            'scope'       => 'required|in:hotel,room',
        ]);

        Amenity::create($d);
        return redirect()->route('admin.amenities.index')->with('success', "Fasilitas '{$d['name']}' berhasil ditambahkan.");
    }

    public function edit(Amenity $amenity)
    {
        return view('admin.amenities.edit', compact('amenity'));
    }

    public function update(Request $request, Amenity $amenity)
    {
        $d = $request->validate([
            'name'        => 'required|string|max:100',
            'description' => 'nullable|string|max:255',
            'scope'       => 'required|in:hotel,room',
        ]);

        $amenity->update($d);
        return redirect()->route('admin.amenities.index')->with('success', "Fasilitas '{$amenity->name}' berhasil diperbarui.");
    }

    public function destroy(Amenity $amenity)
    {
        $name = $amenity->name;
        $amenity->roomTypes()->detach();
        $amenity->delete();
        return redirect()->route('admin.amenities.index')->with('success', "Fasilitas '$name' berhasil dihapus.");
    }
}
