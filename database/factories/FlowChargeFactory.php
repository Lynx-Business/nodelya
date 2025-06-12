<?php

namespace Database\Factories;

use App\Models\FlowCategory;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FlowCharge>
 */
class FlowChargeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'team_id'          => Team::factory(),
            'flow_category_id' => FlowCategory::factory(),
            'amount_in_cents'  => fake()->randomNumber(9, strict: true),
            'charged_at'       => fake()->dateTimeBetween('now', '+1 year'),
        ];
    }
}
