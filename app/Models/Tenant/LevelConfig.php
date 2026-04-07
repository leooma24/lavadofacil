<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class LevelConfig extends Model
{
    protected $fillable = [
        'level', 'min_visits', 'min_spent', 'multiplier',
        'perks', 'color', 'icon', 'sort_order',
    ];

    protected $casts = [
        'perks' => 'array',
        'multiplier' => 'decimal:2',
        'min_spent' => 'decimal:2',
    ];
}
