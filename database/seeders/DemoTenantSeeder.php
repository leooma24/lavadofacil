<?php

namespace Database\Seeders;

use App\Models\Plan;
use App\Models\Tenant;
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

        // Create domains for both local and prod
        if (!$tenant->domains()->where('domain', 'lavadodemo.lavadofacil.test')->exists()) {
            $tenant->domains()->create(['domain' => 'lavadodemo.lavadofacil.test']);
        }
        if (!$tenant->domains()->where('domain', 'lavadodemo.lavadofacil.tu-app.co')->exists()) {
            $tenant->domains()->create(['domain' => 'lavadodemo.lavadofacil.tu-app.co']);
        }

        $this->command->info("✓ Tenant demo creado: lavadodemo (BD: tenant_lavadodemo)");
        $this->command->info("  → http://lavadodemo.lavadofacil.test");
        $this->command->info("  → http://lavadodemo.lavadofacil.test/admin");
    }
}
