<?php

namespace Database\Factories;

use App\Enums\ChargerTypes;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Charger>
 */
class ChargerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->name,
            'type' => fake()->randomElement(
                            [ChargerTypes::SHUKO,
                            ChargerTypes::TYPE2,
                            ChargerTypes::CCS,
                            ChargerTypes::CHADEMO]),
            'available' => fake()->numberBetween(0, 2),
            'faulty' => fake()->numberBetween(0, 2),
            'total' => fake()->numberBetween(4, 6),
        ];
    }
}
