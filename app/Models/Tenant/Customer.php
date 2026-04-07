<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Customer extends Model
{
    protected $fillable = [
        'name', 'phone', 'email', 'birthdate', 'level',
        'total_visits', 'total_spent', 'points_balance',
        'current_streak', 'longest_streak', 'last_streak_date',
        'last_visit_at', 'registered_at', 'referred_by_id',
        'whatsapp_opt_in', 'is_vip', 'vip_until',
        'notes', 'tags',
    ];

    protected $casts = [
        'birthdate' => 'date',
        'last_visit_at' => 'datetime',
        'registered_at' => 'datetime',
        'last_streak_date' => 'date',
        'vip_until' => 'datetime',
        'whatsapp_opt_in' => 'boolean',
        'is_vip' => 'boolean',
        'tags' => 'array',
        'total_spent' => 'decimal:2',
    ];

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class);
    }

    public function visits(): HasMany
    {
        return $this->hasMany(Visit::class);
    }

    public function loyaltyCard(): HasOne
    {
        return $this->hasOne(LoyaltyCard::class);
    }

    public function pointTransactions(): HasMany
    {
        return $this->hasMany(PointTransaction::class);
    }

    public function referredBy(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'referred_by_id');
    }

    public function referrals(): HasMany
    {
        return $this->hasMany(Customer::class, 'referred_by_id');
    }

    public function raffleTickets(): HasMany
    {
        return $this->hasMany(RaffleTicket::class);
    }

    public function vipSubscription(): HasOne
    {
        return $this->hasOne(VipSubscription::class)->where('status', 'active');
    }

    public function packages(): HasMany
    {
        return $this->hasMany(CustomerPackage::class);
    }

    public function whatsappMessages(): HasMany
    {
        return $this->hasMany(WhatsappMessage::class);
    }
}
