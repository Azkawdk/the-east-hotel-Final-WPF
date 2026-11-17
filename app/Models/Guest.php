<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Guest extends Model
{
    use SoftDeletes;

    protected $fillable = ['full_name', 'email', 'phone', 'country', 'id_number'];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
