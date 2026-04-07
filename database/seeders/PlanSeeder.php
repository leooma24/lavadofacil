<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Básico',
                'slug' => 'basico',
                'description' => 'Ideal para car washes pequeños que apenas inician con fidelización',
                'price_monthly' => 299,
                'price_yearly' => 2990,
                'max_customers' => 500,
                'max_locations' => 1,
                'max_staff' => 2,
                'features' => [
                    'loyalty_card' => true,
                    'rewards_wheel' => true,
                    'raffle' => false,
                    'levels' => false,
                    'streaks' => false,
                    'birthdays' => true,
                    'referrals' => true,
                    'whatsapp' => true,
                    'reactivation' => false,
                    'ranking' => false,
                    'challenges' => false,
                    'surveys' => false,
                    'vip' => false,
                    'prepaid' => false,
                    'forecast' => false,
                ],
                'sort_order' => 1,
            ],
            [
                'name' => 'Pro',
                'slug' => 'pro',
                'description' => 'Para car washes establecidos que quieren dominar la fidelización',
                'price_monthly' => 599,
                'price_yearly' => 5990,
                'max_customers' => 2000,
                'max_locations' => 2,
                'max_staff' => 5,
                'features' => [
                    'loyalty_card' => true,
                    'rewards_wheel' => true,
                    'raffle' => true,
                    'levels' => true,
                    'streaks' => true,
                    'birthdays' => true,
                    'referrals' => true,
                    'whatsapp' => true,
                    'reactivation' => true,
                    'ranking' => true,
                    'challenges' => true,
                    'surveys' => true,
                    'vip' => false,
                    'prepaid' => true,
                    'forecast' => false,
                ],
                'sort_order' => 2,
            ],
            [
                'name' => 'Elite',
                'slug' => 'elite',
                'description' => 'Multi-sucursal con predicción de ganancias y todo desbloqueado',
                'price_monthly' => 1299,
                'price_yearly' => 12990,
                'max_customers' => null,
                'max_locations' => 10,
                'max_staff' => 20,
                'features' => [
                    'loyalty_card' => true,
                    'rewards_wheel' => true,
                    'raffle' => true,
                    'levels' => true,
                    'streaks' => true,
                    'birthdays' => true,
                    'referrals' => true,
                    'whatsapp' => true,
                    'reactivation' => true,
                    'ranking' => true,
                    'challenges' => true,
                    'surveys' => true,
                    'vip' => true,
                    'prepaid' => true,
                    'forecast' => true,
                ],
                'sort_order' => 3,
            ],
        ];

        foreach ($plans as $plan) {
            Plan::updateOrCreate(['slug' => $plan['slug']], $plan);
        }
    }
}
