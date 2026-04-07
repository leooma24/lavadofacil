<?php

namespace App\Filament\Resources\VisitResource\Pages;

use App\Filament\Resources\VisitResource;
use App\Services\VisitRegistrar;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateVisit extends CreateRecord
{
    protected static string $resource = VisitResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $visit = app(VisitRegistrar::class)->register(
            customerId: (int) $data['customer_id'],
            items: $data['items'] ?? [],
            discount: (float) ($data['discount'] ?? 0),
            paymentMethod: $data['payment_method'] ?? 'cash',
            notes: $data['notes'] ?? null,
        );

        Notification::make()
            ->title('Visita registrada')
            ->body("Total \${$visit->total} · {$visit->earned_stamps} sello(s)")
            ->success()
            ->send();

        return $visit;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
