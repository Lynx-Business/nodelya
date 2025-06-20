<?php

namespace Database\Factories;

use App\Enums\Expense\ExpenseType;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ExpenseCategory>
 */
class ExpenseCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'team_id' => Team::factory(),
            'type'    => fake()->randomElement(ExpenseType::cases()),
            'name'    => fake()->companySuffix(),
        ];
    }
}
