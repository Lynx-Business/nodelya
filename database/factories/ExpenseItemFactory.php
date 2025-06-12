<?php

namespace Database\Factories;

use App\Models\ExpenseCategory;
use App\Models\ExpenseSubCategory;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ExpenseItem>
 */
class ExpenseItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'team_id'                 => Team::factory(),
            'expense_category_id'     => ExpenseCategory::factory(),
            'expense_sub_category_id' => ExpenseSubCategory::factory(),
            'name'                    => fake()->jobTitle(),
        ];
    }
}
