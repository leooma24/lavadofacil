<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class MessageTemplate extends Model
{
    protected $fillable = [
        'channel', 'type', 'name', 'subject', 'body', 'variables', 'is_active',
    ];

    protected $casts = [
        'variables' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Render the template body replacing variables with customer data.
     */
    public function render(Customer $customer, array $extra = []): string
    {
        $vars = array_merge([
            'nombre' => $customer->name,
            'telefono' => $customer->phone,
            'nivel' => ucfirst($customer->level),
            'visitas' => $customer->total_visits,
            'puntos' => $customer->points_balance,
            'racha' => $customer->current_streak,
        ], $extra);

        $body = $this->body;
        foreach ($vars as $key => $value) {
            $body = str_replace('{' . $key . '}', (string) $value, $body);
        }
        return $body;
    }
}
