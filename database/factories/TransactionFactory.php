<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = $this->faker->randomElement(['income', 'expense']);

        return [
            'type' => $type,
            'label' => $this->faker->word(),
            'amount' => $this->faker->randomFloat(2, 10, 10000),
            'account_id' => Account::factory(),
            'category_id' => Category::factory()->state(['type' => $type]),
            'description' => $this->faker->sentence(),
            'transaction_date' => $this->faker->dateTimeBetween('-6 months', 'now')->format('Y-m-d'),
        ];
    }
}
