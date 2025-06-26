<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Budget;
use App\Models\Category;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PersonalAccountingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory(10)->create()->each(function ($user) {
            // ✅ Create accounts with a valid UUID from the user
            $accounts = Account::factory(5)->create([
                'user_id' => $user->id,
            ]);

            // ✅ Create categories
            $incomeCategories = Category::factory(5)->income()->create([
                'user_id' => $user->id,
            ]);

            $expenseCategories = Category::factory(5)->expense()->create([
                'user_id' => $user->id,
            ]);

            $allCategories = $incomeCategories->merge($expenseCategories);

            // ✅ Create budgets
            foreach ($allCategories as $category) {
                Budget::factory()->create([
                    'user_id' => $user->id,
                    'category_id' => $category->id,
                    'month' => now()->startOfMonth()->format('Y-m-01'),
                ]);
            }

            // ✅ Create transactions
            Transaction::factory(10)->create([
                'user_id' => $user->id,
                'account_id' => $accounts->random()->id,
                'category_id' => $allCategories->random()->id,
            ]);
        });
    }
}
