<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'address' => fake()->address,
            'city' => fake()->city,
            'country_code' => fake()->countryCode,
            'postal_code' => fake()->postcode,
            'lat' => fake()->latitude,
            'lng' => fake()->longitude,
        ];
    }

    public function addressable(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'addressable_id' => $attributes['addressable_id'],
                'addressable_type' => $attributes['addressable_type'],
            ];
        });
    }
}
