<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChallengeProgress extends Model
{
    protected $table = 'challenge_progress';

    protected $fillable = [
        'challenge_id', 'customer_id', 'current_value', 'completed_at', 'claimed_at',
    ];

    protected $casts = [
        'current_value' => 'decimal:2',
        'completed_at' => 'datetime',
        'claimed_at' => 'datetime',
    ];

    public function challenge(): BelongsTo
    {
        return $this->belongsTo(MonthlyChallenge::class, 'challenge_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
