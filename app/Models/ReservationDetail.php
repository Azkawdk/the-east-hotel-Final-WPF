<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReservationDetail extends Model
{
    protected $fillable = ['reservation_id', 'room_id', 'room_type_id', 'price_per_night', 'nights', 'subtotal'];

    protected $casts = ['price_per_night' => 'float', 'subtotal' => 'float'];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class)->withTrashed();
    }

    public function roomType()
    {
        return $this->belongsTo(RoomType::class)->withTrashed();
    }
}
