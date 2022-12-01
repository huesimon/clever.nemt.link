<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Location>
 */
class LocationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'external_id' => $this->faker->uuid,
            'name' => $this->faker->name,
            'origin' => $this->faker->name,
            'is_roaming_allowed' => $this->faker->boolean,
            'is_public_visible' => $this->faker->boolean,
            'coordinates' => $this->faker->latitude . ', ' . $this->faker->longitude,
        ];
    }
}
