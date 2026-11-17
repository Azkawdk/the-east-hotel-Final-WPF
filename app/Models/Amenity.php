<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Amenity extends Model
{
    protected $fillable = ['name', 'description', 'scope'];

    public function roomTypes()
    {
        return $this->belongsToMany(RoomType::class, 'amenity_room_type');
    }
}
