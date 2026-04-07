<?php

namespace App\Models\Tenant;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Visit extends Model
{
    protected $fillable = [
        'customer_id', 'vehicle_id', 'location_id', 'served_by_user_id',
        'subtotal', 'discount', 'total', 'payment_method',
        'earned_stamps', 'points_earned', 'satisfaction_rating',
        'notes', 'visited_at',
    ];

    protected $casts = [
        'visited_at' => 'datetime',
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function servedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'served_by_user_id');
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'visit_services')
            ->withPivot(['quantity', 'unit_price', 'subtotal']);
    }

    public function survey(): HasOne
    {
        return $this->hasOne(Survey::class);
    }
}
