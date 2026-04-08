<?php

namespace App\Filament\Central\Resources\TenantResource\Pages;

use App\Filament\Central\Resources\TenantResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTenant extends CreateRecord
{
    protected static string $resource = TenantResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Use slug as the tenant ID (so DB becomes tenant_{slug})
        // Con path-based tenancy el slug es el identificador en la URL,
        // no se crean records en la tabla `domains`.
        $data['id'] = $data['slug'];
        return $data;
    }
}
