<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use Illuminate\Http\Request;

class AmenityController extends Controller
{
    private function rules(): array
    {
        return ['name' => 'required|string|max:100', 'description' => 'nullable|string|max:255', 'scope' => 'required|in:hotel,room'];
    }

    public function index()
    {
        return Amenity::orderBy('scope')->orderBy('name')->get();
    }

    public function store(Request $request)
    {
        return response()->json(Amenity::create($request->validate($this->rules())), 201);
    }

    public function update(Request $request, Amenity $amenity)
    {
        $amenity->update($request->validate($this->rules()));

        return $amenity;
    }

    public function destroy(Amenity $amenity)
    {
        $amenity->roomTypes()->detach();
        $amenity->delete();

        return response()->noContent();
    }
}
