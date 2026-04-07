<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class DailyStat extends Model
{
    protected $fillable = [
        'date', 'visits_count', 'revenue',
        'new_customers', 'active_customers', 'dormant_customers',
        'stamps_given', 'prizes_claimed', 'created_at',
    ];

    public $timestamps = false;

    protected $casts = [
        'date' => 'date',
        'created_at' => 'datetime',
        'revenue' => 'decimal:2',
    ];
}
