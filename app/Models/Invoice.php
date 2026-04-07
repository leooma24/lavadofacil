<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    protected $fillable = [
        'tenant_id', 'subscription_id', 'invoice_number',
        'amount', 'currency', 'status', 'paid_at',
        'stripe_invoice_id', 'meta',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'meta' => 'array',
        'amount' => 'decimal:2',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }
}
