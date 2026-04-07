<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LoyaltyCard extends Model
{
    protected $fillable = [
        'customer_id', 'stamps_count', 'completed_count',
        'current_card_number', 'started_at', 'last_stamp_at',
        'is_complete', 'completed_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'last_stamp_at' => 'datetime',
        'completed_at' => 'datetime',
        'is_complete' => 'boolean',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function stamps(): HasMany
    {
        return $this->hasMany(Stamp::class);
    }

    public function spins(): HasMany
    {
        return $this->hasMany(PrizeSpin::class);
    }
}
