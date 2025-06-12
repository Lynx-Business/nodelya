<?php

namespace Database\Factories;

use App\Models\ExpenseCategory;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ExpenseSubCategory>
 */
class ExpenseSubCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'team_id'             => Team::factory(),
            'expense_category_id' => ExpenseCategory::factory(),
            'name'                => fake()->jobTitle(),
        ];
    }
}
