<?php

namespace Database\Factories;

use App\Models\AccountingPeriod;
use App\Models\ExpenseItem;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ExpenseBudget>
 */
class ExpenseBudgetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'team_id'         => Team::factory(),
            'expense_item_id' => ExpenseItem::factory(),
            'amount_in_cents' => fake()->randomNumber(9, strict: true),
            'starts_at'       => fake()->dateTimeBetween('now', '+1 year'),
            'ends_at'         => fn (array $attributes) => fake()->dateTimeBetween(data_get($attributes, 'starts_at'), '+3 years'),
        ];
    }

    public function forAccountingPeriod(AccountingPeriod $accountingPeriod): static
    {
        return $this->state(fn (array $attributes) => [
            'starts_at' => $accountingPeriod->starts_at,
            'ends_at'   => $accountingPeriod->ends_at,
        ]);
    }
}
