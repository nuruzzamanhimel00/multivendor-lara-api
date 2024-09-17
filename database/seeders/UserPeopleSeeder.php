<?php

namespace Database\Seeders;

use App\Models\login;
use App\Models\UserPeople;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserPeopleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserPeople::factory()
        ->count(10) // Number of users to create
        // ->has(login::factory()->count(25)) // Number of logins per user
        ->create();
    }
}
