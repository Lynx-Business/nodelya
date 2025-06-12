<?php

namespace Database\Factories;

use App\Data\Deal\DealScheduleData;
use App\Enums\Deal\DealStatus;
use App\Models\Client;
use App\Models\ProjectDepartment;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Deal>
 */
class DealFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'team_id'               => Team::factory(),
            'project_department_id' => ProjectDepartment::factory(),
            'client_id'             => Client::factory(),
            'name'                  => fake()->company(),
            'status'                => fake()->randomElement(DealStatus::cases()),
            'amount_in_cents'       => fake()->randomNumber(9, strict: true),
            'code'                  => fake()->countryCode(),
            'reference'             => fn (array $attributes) => data_get($attributes, 'status') == DealStatus::CREATED ? null : Str::random(10),
            'success_rate'          => fake()->biasedNumberBetween(),
            'ordered_at'            => fake()->dateTimeThisYear(),
            'duration_in_months'    => fake()->randomNumber(2),
            'starts_at'             => fake()->dateTimeThisYear(),
            'schedule'              => DealScheduleData::from([]),
        ];
    }
}
