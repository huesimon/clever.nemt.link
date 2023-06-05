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
            'connector_id' => 1,
            'max_current_amp' => $this->faker->randomElement([
                16,
            ]),
            'max_power_kw' => $this->faker->randomElement([
                11,
                22,
            ]),
            'plug_type' => $this->faker->randomElement([
                'Type 2',
            ]),
            'speed' => $this->faker->randomElement([
                'Standard',
            ]),
        ];
    }
}
