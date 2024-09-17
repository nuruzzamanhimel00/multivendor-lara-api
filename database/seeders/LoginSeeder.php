<?php

namespace Database\Seeders;

use App\Models\login;
use Illuminate\Database\Seeder;
use Database\Factories\LoginFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LoginSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        login::factory()->count(50)->create();
    }
}
