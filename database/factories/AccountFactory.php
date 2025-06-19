<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Account>
 */
class AccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word() . ' Account',
            'user_id' => User::factory(), // creates a related user automatically
            'type' => $this->faker->randomElement(['cash', 'bank', 'mpesa', 'others']),
            'balance' => $this->faker->randomFloat(2, 0, 100000),
        ];
    }
}
