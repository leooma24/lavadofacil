<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed central database. Tenant data is seeded automatically when each tenant is created
     * (vía TenancyServiceProvider listening to TenantCreated event).
     */
    public function run(): void
    {
        $this->call([
            PlanSeeder::class,
            CentralUserSeeder::class,
            DemoTenantSeeder::class,
        ]);
    }
}
