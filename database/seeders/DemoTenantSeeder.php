<?php

namespace Database\Seeders;

use App\Models\Plan;
use App\Models\Tenant;
use Database\Seeders\Tenant\TenantDemoDataSeeder;
use Illuminate\Database\Seeder;

class DemoTenantSeeder extends Seeder
{
    public function run(): void
    {
        $plan = Plan::where('slug', 'pro')->first();

        $tenant = Tenant::updateOrCreate(
            ['id' => 'lavadodemo'],
            [
                'name' => 'Lavado Demo',
                'slug' => 'lavadodemo',
                'owner_name' => 'Omar Lerma',
                'owner_email' => 'leooma24@gmail.com',
                'owner_phone' => '6682493398',
                'plan_id' => $plan?->id,
                'status' => 'trial',
                'trial_ends_at' => now()->addDays(30),
                'primary_color' => '#0ea5e9',
                'timezone' => 'America/Mazatlan',
                'currency' => 'MXN',
            ]
        );

        // Path-based tenancy: ya no se crean domains. El slug ES el identificador
        // y vive como segmento de path en la URL del dominio central.

        $this->command->info("✓ Tenant demo creado: lavadodemo (BD: tenant_lavadodemo)");
        $this->command->info("  → http://lavadofacil.test/lavadodemo");
        $this->command->info("  → http://lavadofacil.test/lavadodemo/admin");

        // Sembrar data demo dentro del contexto del tenant
        tenancy()->initialize($tenant);
        try {
            $this->call(TenantDemoDataSeeder::class);
        } finally {
            tenancy()->end();
        }
    }
}
