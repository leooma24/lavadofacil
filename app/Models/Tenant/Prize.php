<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Prize extends Model
{
    protected $fillable = [
        'name', 'description', 'image', 'type', 'value',
        'probability_weight', 'stock', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'value' => 'decimal:2',
    ];

    public function spins(): HasMany
    {
        return $this->hasMany(PrizeSpin::class);
    }
}
