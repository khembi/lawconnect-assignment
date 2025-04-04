<?php

namespace Database\Factories;

use App\Models\Topic;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Topic>
 */
class TopicFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(),
            'content' => $this->faker->paragraphs(3, true),
            'views' => $this->faker->numberBetween(0, 1000),
            'status' => Topic::STATUS_PUBLISHED,
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => function (array $attributes) {
                return $this->faker->dateTimeBetween($attributes['created_at'], 'now');
            },
        ];
    }

    /**
     * Indicate that the topic is a draft.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function draft(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Topic::STATUS_DRAFT,
            ];
        });
    }

    /**
     * Indicate that the topic is published.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function published(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Topic::STATUS_PUBLISHED,
            ];
        });
    }
}
