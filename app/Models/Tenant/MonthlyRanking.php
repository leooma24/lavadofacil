<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MonthlyRanking extends Model
{
    protected $fillable = [
        'month', 'year', 'customer_id', 'position',
        'visits_count', 'total_spent', 'created_at',
    ];

    public $timestamps = false;

    protected $casts = [
        'created_at' => 'datetime',
        'total_spent' => 'decimal:2',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
