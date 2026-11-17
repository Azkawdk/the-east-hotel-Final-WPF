<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    public const TRANSITIONS = [
        'pending' => ['paid', 'failed'],
        'failed' => ['pending', 'paid'],
        'paid' => ['refunded'],
        'refunded' => [],
    ];

    protected $fillable = ['reservation_id', 'amount', 'method', 'status', 'reference', 'paid_at', 'notes', 'processed_by'];

    protected $casts = ['amount' => 'float', 'paid_at' => 'datetime'];

    protected $appends = ['allowed_transitions'];

    public function getAllowedTransitionsAttribute(): array
    {
        return self::TRANSITIONS[$this->status] ?? [];
    }

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
}
