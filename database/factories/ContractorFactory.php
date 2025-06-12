<?php

namespace Database\Factories;

use App\Models\ProjectDepartment;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contractor>
 */
class ContractorFactory extends Factory
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
        ];
    }
}
