<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MonthlyChallenge extends Model
{
    protected $fillable = [
        'name', 'description', 'image', 'month', 'year',
        'goal_type', 'goal_value', 'reward_description',
        'reward_points', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'goal_value' => 'decimal:2',
    ];

    public function progress(): HasMany
    {
        return $this->hasMany(ChallengeProgress::class, 'challenge_id');
    }
}
