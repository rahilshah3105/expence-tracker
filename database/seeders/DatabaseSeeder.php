<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Categories
        $food = \App\Models\Category::create(['name' => 'Food & Dining', 'color' => '#ef4444']); // Red
        $transport = \App\Models\Category::create(['name' => 'Transportation', 'color' => '#f59e0b']); // Amber
        $utilities = \App\Models\Category::create(['name' => 'Utilities', 'color' => '#3b82f6']); // Blue
        $entertainment = \App\Models\Category::create(['name' => 'Entertainment', 'color' => '#8b5cf6']); // Violet

        // Create Expenses
        \App\Models\Expense::create([
            'description' => 'Grocery Shopping',
            'amount' => 125.50,
            'date' => now()->subDays(2),
            'category_id' => $food->id,
        ]);
        
        \App\Models\Expense::create([
            'description' => 'Uber to Work',
            'amount' => 18.00,
            'date' => now()->subDays(1),
            'category_id' => $transport->id,
        ]);

        \App\Models\Expense::create([
            'description' => 'Netflix Subscription',
            'amount' => 15.99,
            'date' => now()->startOfMonth(),
            'category_id' => $entertainment->id,
        ]);

        \App\Models\Expense::create([
            'description' => 'Electric Bill',
            'amount' => 85.00,
            'date' => now()->subDays(5),
            'category_id' => $utilities->id,
        ]);
    }
}
