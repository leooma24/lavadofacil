<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
    protected $fillable = [
        'customer_id', 'service_id', 'type', 'address',
        'scheduled_at', 'queue_position', 'status',
        'notes', 'ready_notified_at',
        'customer_response', 'responded_at',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'ready_notified_at' => 'datetime',
        'responded_at' => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'pending' => 'Pendiente',
            'confirmed' => 'Confirmada',
            'in_queue' => 'En cola',
            'in_progress' => 'En proceso',
            'ready' => 'Listo para traer',
            'completed' => 'Terminada',
            'cancelled' => 'Cancelada',
            default => $this->status,
        };
    }
}
