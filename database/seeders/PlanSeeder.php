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
                    'ruleta' => true,
                    'rifa' => false,
                    'niveles' => true,
                    'referidos' => true,
                    'cumpleanos' => true,
                    'racha' => false,
                    'reactivacion' => false,
                    'ranking' => false,
                    'reto' => false,
                    'encuesta' => false,
                    'vip' => false,
                    'paquetes' => true,
                    'prediccion' => false,
                    'whatsapp' => true,
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
                    'ruleta' => true,
                    'rifa' => true,
                    'niveles' => true,
                    'referidos' => true,
                    'cumpleanos' => true,
                    'racha' => true,
                    'reactivacion' => true,
                    'ranking' => true,
                    'reto' => true,
                    'encuesta' => true,
                    'vip' => true,
                    'paquetes' => true,
                    'prediccion' => false,
                    'whatsapp' => true,
                ],
                'sort_order' => 2,
            ],
            [
                'name' => 'Elite',
                'slug' => 'elite',
                'description' => 'Multi-sucursal con predicción de ganancias y todo desbloqueado',
                'price_monthly' => 1299,
                'price_yearly' => 12990,
                'max_customers' => null, // ilimitado
                'max_locations' => 10,
                'max_staff' => 20,
                'features' => [
                    'ruleta' => true,
                    'rifa' => true,
                    'niveles' => true,
                    'referidos' => true,
                    'cumpleanos' => true,
                    'racha' => true,
                    'reactivacion' => true,
                    'ranking' => true,
                    'reto' => true,
                    'encuesta' => true,
                    'vip' => true,
                    'paquetes' => true,
                    'prediccion' => true,
                    'whatsapp' => true,
                ],
                'sort_order' => 3,
            ],
        ];

        foreach ($plans as $plan) {
            Plan::updateOrCreate(['slug' => $plan['slug']], $plan);
        }
    }
}
