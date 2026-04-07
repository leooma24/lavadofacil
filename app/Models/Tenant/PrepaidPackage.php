<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class PrepaidPackage extends Model
{
    protected $fillable = [
        'name', 'description', 'washes_count', 'price',
        'validity_days', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price' => 'decimal:2',
    ];
}
