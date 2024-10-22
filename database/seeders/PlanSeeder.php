<?php

namespace Database\Seeders;

use App\Models\Plan;
use App\Models\login;
use Illuminate\Database\Seeder;
use Database\Factories\LoginFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'id' => 1,
                'name' => 'Lite',
                'limit_items' => 10,
                'limit_orders' => 10,
                'price'=>0,
                'period'=>1,
                'plan_description'=>'Use it for free and upgrade as you grow',
                'plan_features'=>'Use it for free and upgrade as you grow',
                'enable_orders'=> 2,
            ],
            [
                'id' => 2,
                'name' => 'Pro',
                'limit_items' => 50,
                'limit_orders' => 50,
                'price'=>100,
                'period'=>2,
                'plan_description'=>'Use it for free and upgrade as you grow',
                'plan_features'=>'Use it for free and upgrade as you grow',
                'enable_orders'=> 2,
            ]
        ];

        foreach ($plans as $plan) {
            if(in_array($plan['id'],[1,2])){
                Plan::updateOrCreate(
                    ['id' => $plan['id']],
                    $plan
                );
            }

        }
    }
}
