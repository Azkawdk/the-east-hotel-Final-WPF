<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Room extends Model
{
    use SoftDeletes;

    protected $fillable = ['room_type_id', 'room_number', 'floor', 'status', 'notes'];

    public function roomType()
    {
        return $this->belongsTo(RoomType::class)->withTrashed();
    }
}
