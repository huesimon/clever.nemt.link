<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Feedback>
 */
class FeedbackFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'topic' => $this->faker->sentence,
            'email' => $this->faker->email,
            'message' => $this->faker->paragraph,
        ];
    }

    /**
     * Indicate that the feedback has been published.
     */
    public function published(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'published_at' => now(),
            ];
        });
    }

    /**
     * Indicate that the feedback has been responded to.
     */

    public function responded(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'response' => $this->faker->paragraph,
            ];
        });
    }
}
