<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Raffle extends Model
{
    protected $fillable = [
        'name', 'description', 'prize_description', 'prize_image',
        'month', 'year', 'tickets_required', 'max_tickets_per_customer',
        'draw_date', 'status', 'winner_customer_id', 'winning_ticket_number',
    ];

    protected $casts = ['draw_date' => 'date'];

    public function tickets(): HasMany
    {
        return $this->hasMany(RaffleTicket::class);
    }

    public function winner(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'winner_customer_id');
    }
}
