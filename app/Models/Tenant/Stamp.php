<?php

namespace App\Models\Tenant;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Stamp extends Model
{
    protected $fillable = [
        'loyalty_card_id', 'visit_id', 'stamped_by_user_id', 'stamped_at',
    ];

    protected $casts = ['stamped_at' => 'datetime'];

    public function loyaltyCard(): BelongsTo
    {
        return $this->belongsTo(LoyaltyCard::class);
    }

    public function visit(): BelongsTo
    {
        return $this->belongsTo(Visit::class);
    }

    public function stampedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'stamped_by_user_id');
    }
}
