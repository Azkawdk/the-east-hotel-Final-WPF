<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RoomType extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'slug', 'description', 'capacity', 'base_price', 'size_m2', 'bed_type', 'image_url', 'is_active'];

    protected $casts = ['base_price' => 'float', 'is_active' => 'boolean', 'capacity' => 'integer'];

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function amenities()
    {
        return $this->belongsToMany(Amenity::class, 'amenity_room_type');
    }
}
