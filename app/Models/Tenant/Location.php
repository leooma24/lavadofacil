<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = [
        'name', 'address', 'phone', 'lat', 'lng', 'is_active', 'sort_order',
    ];

    protected $casts = ['is_active' => 'boolean'];
}
