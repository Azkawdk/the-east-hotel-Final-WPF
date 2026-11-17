<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    /** Status yang menahan kamar (dipakai pada pengecekan ketersediaan). */
    public const ACTIVE = ['pending', 'confirmed', 'checked_in'];

    /** Perubahan status yang diperbolehkan. */
    public const TRANSITIONS = [
        'pending' => ['confirmed', 'cancelled'],
        'confirmed' => ['checked_in', 'cancelled'],
        'checked_in' => ['checked_out'],
        'checked_out' => [],
        'cancelled' => [],
    ];

    protected $fillable = [
        'code', 'guest_id', 'check_in', 'check_out', 'guests_count', 'nights',
        'subtotal', 'service_amount', 'tax_amount', 'total_amount', 'status',
        'special_requests', 'expires_at', 'cancelled_at', 'cancel_reason', 'created_by',
    ];

    protected $casts = [
        'check_in' => 'date:Y-m-d',
        'check_out' => 'date:Y-m-d',
        'expires_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'subtotal' => 'float', 'service_amount' => 'float', 'tax_amount' => 'float', 'total_amount' => 'float',
    ];

    protected $appends = ['allowed_transitions'];

    public function getAllowedTransitionsAttribute(): array
    {
        return self::TRANSITIONS[$this->status] ?? [];
    }

    public function guest()
    {
        return $this->belongsTo(Guest::class)->withTrashed();
    }

    public function details()
    {
        return $this->hasMany(ReservationDetail::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
