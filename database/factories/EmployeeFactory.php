<?php

namespace Database\Factories;

use App\Models\ProjectDepartment;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
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
            'first_name'            => fake()->firstName(),
            'last_name'             => fake()->lastName(),
            'phone'                 => fake()->phoneNumber(),
            'email'                 => fake()->safeEmail(),
            'starts_at'             => fake()->dateTimeBetween('now', '+1 year'),
            'ends_at'               => fn (array $attributes) => fake()->dateTimeBetween(data_get($attributes, 'starts_at'), '+3 years'),
        ];
    }
}
