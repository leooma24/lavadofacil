<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Survey extends Model
{
    protected $fillable = [
        'visit_id', 'customer_id', 'rating', 'nps',
        'comments', 'would_recommend', 'answered_at',
    ];

    protected $casts = [
        'answered_at' => 'datetime',
        'would_recommend' => 'boolean',
    ];

    public function visit(): BelongsTo
    {
        return $this->belongsTo(Visit::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
