<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use Database\Seeders\Tenant\TenantDemoDataSeeder;
use Illuminate\Console\Command;

class DemoReseedCommand extends Command
{
    protected $signature = 'demo:reseed {tenant=lavadodemo : ID del tenant a resembrar}';
    protected $description = 'Resiembra el tenant demo con clientes, visitas, rifas y tarjetas';

    public function handle(): int
    {
        $tenantId = $this->argument('tenant');
        $tenant = Tenant::find($tenantId);

        if (! $tenant) {
            $this->error("Tenant '{$tenantId}' no existe");
            return self::FAILURE;
        }

        $this->info("Resembrando tenant: {$tenant->name}");

        tenancy()->initialize($tenant);
        try {
            $this->call('db:seed', [
                '--class' => TenantDemoDataSeeder::class,
                '--force' => true,
            ]);
        } finally {
            tenancy()->end();
        }

        $this->info('✓ Listo');
        return self::SUCCESS;
    }
}
