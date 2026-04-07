<?php

namespace App\Services;

use App\Models\Tenant\Customer;
use App\Models\Tenant\MessageTemplate;

/**
 * Builds wa.me links for manual WhatsApp sending.
 *
 * Used in Filament Resources via Action::make()->url(...)->openUrlInNewTab().
 * No Meta Cloud API integration — the owner clicks a button and WhatsApp Web opens
 * with the message pre-filled. Audit happens in `whatsapp_messages` when owner
 * clicks "Marcar enviado".
 */
class WhatsAppLinkBuilder
{
    public static function build(string $phone, string $message): string
    {
        $phone = self::formatPhone($phone);
        return 'https://wa.me/' . $phone . '?text=' . urlencode($message);
    }

    public static function fromTemplate(MessageTemplate $template, Customer $customer, array $vars = []): string
    {
        $body = $template->render($customer, $vars);
        return self::build($customer->phone, $body);
    }

    public static function quick(Customer $customer, string $message): string
    {
        return self::build($customer->phone, $message);
    }

    private static function formatPhone(string $phone): string
    {
        $phone = preg_replace('/[\s\-\(\)\+]/', '', $phone);
        if (strlen($phone) === 10) {
            $phone = '52' . $phone;
        }
        return $phone;
    }
}
