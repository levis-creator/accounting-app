<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'type' => $this->faker->randomElement(['income', 'expense']),
        ];
    }

    /**
     * Optional state for income type
     */
    public function income(): static
    {
        return $this->state(fn() => ['type' => 'income']);
    }

    /**
     * Optional state for expense type
     */
    public function expense(): static
    {
        return $this->state(fn() => ['type' => 'expense']);
    }
}
