<?php

namespace Database\Factories;

use App\Data\Address\AddressData;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'team_id' => Team::factory(),
            'name'    => fake()->company(),
            'address' => AddressData::from([
                'address'            => fake()->streetAddress(),
                'address_complement' => fake()->optional()->secondaryAddress(),
                'city'               => fake()->city(),
                'postal_code'        => fake()->postcode(),
                'country'            => fake()->country(),
            ]),
        ];
    }
}
