<?php

namespace Database\Factories;

use App\Models\AccountingPeriod;
use App\Models\ExpenseItem;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ExpenseCharge>
 */
class ExpenseChargeFactory extends Factory
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
            'charged_at'      => fake()->dateTimeBetween('now', '+1 year'),
        ];
    }

    public function forAccountingPeriod(AccountingPeriod $accountingPeriod): static
    {
        return $this->state(fn (array $attributes) => [
            'charged_at' => fake()->dateTimeBetween($accountingPeriod->starts_at, $accountingPeriod->ends_at),
        ]);
    }
}
