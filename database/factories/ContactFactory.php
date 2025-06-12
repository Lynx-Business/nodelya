<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contact>
 */
class ContactFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'team_id'    => Team::factory(),
            'client_id'  => Client::factory(),
            'is_main'    => fake()->boolean(25),
            'first_name' => fake()->firstName(),
            'last_name'  => fake()->lastName(),
            'phone'      => fake()->phoneNumber(),
            'email'      => fake()->safeEmail(),
        ];
    }
}
