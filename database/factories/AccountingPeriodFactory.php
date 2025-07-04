<?php

namespace Database\Factories;

use App\Models\Team;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AccountingPeriod>
 */
class AccountingPeriodFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'team_id'   => Team::factory(),
            'starts_at' => Carbon::parse(fake()->dateTimeInInterval('-10 years', '+20 years'))->startOfDay(),
            'ends_at'   => fn (array $attributes) => Carbon::parse($attributes['starts_at'])->addYear()->subDay()->endOfDay(),
        ];
    }
}
