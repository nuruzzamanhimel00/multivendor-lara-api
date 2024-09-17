<?php

namespace Database\Factories;

use App\Models\login;
use App\Models\UserPeople;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Login>
 */
class LoginFactory extends Factory
{
    protected $model = login::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_people_id' =>UserPeople::inRandomOrder()->value('id'), // Assuming a foreign key relationship
            'login_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
