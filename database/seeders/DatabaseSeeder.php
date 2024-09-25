<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'seller@app.com'],
            [
                'name' => 'Test Seller',
                'email' => 'seller@app.com',
                'password' => Hash::make('12345678'),
                'user_type' => User::USER_TYPE_SELLER
            ]);

            User::query()->updateOrCreate(
                ['email' => 'admin@app.com'],
                [
                    'name' => 'Test Admin',
                    'email' => 'admin@app.com',
                    'password' => Hash::make('12345678'),
                    'user_type' => User::USER_TYPE_ADMIN
                ]);
            User::query()->updateOrCreate(
                ['email' => 'user@app.com'],
                [
                    'name' => 'Test User',
                    'email' => 'user@app.com',
                    'password' => Hash::make('12345678'),
                    'user_type' => User::USER_TYPE_USER
                ]);

                $this->call([
                    UserPeopleSeeder::class,
                    LoginSeeder::class,

                ]);
    }
}
