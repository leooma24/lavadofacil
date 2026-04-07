<?php

namespace App\Filament\Central\Resources\TenantResource\Pages;

use App\Filament\Central\Resources\TenantResource;
use App\Models\Tenant;
use Filament\Resources\Pages\CreateRecord;
use Stancl\Tenancy\Database\Models\Domain;

class CreateTenant extends CreateRecord
{
    protected static string $resource = TenantResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Use slug as the tenant ID (so DB becomes tenant_{slug})
        $data['id'] = $data['slug'];
        return $data;
    }

    protected function afterCreate(): void
    {
        /** @var Tenant $tenant */
        $tenant = $this->record;

        // Auto-create the domain for this tenant
        $tenant->domains()->create([
            'domain' => $tenant->slug . '.lavadofacil.test',
        ]);

        // Also create production domain so it works in prod when deployed
        $tenant->domains()->create([
            'domain' => $tenant->slug . '.lavadofacil.tu-app.co',
        ]);
    }
}
