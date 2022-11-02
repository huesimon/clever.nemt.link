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
            'evse_id' => $this->faker->uuid,
            'status' => $this->faker->randomElement([
                'Available',
                'Occupied',
                'Unknown',
            ]),
        ];
    }
}
