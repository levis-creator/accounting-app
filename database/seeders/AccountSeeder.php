<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
    }
    public function definition(): array
    {
        return [
            'name' => fake()->word() . ' Account',
            'type' => fake()->randomElement(['cash', 'bank', 'mpesa', 'others']),
            'balance' => fake()->randomFloat(2, 0, 100000), // e.g., 2.75, 54321.23
        ];
    }
}
