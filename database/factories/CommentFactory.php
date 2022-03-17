<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'poster_id' => $this->faker->numberBetween(1, 99),
            'bookmark_id' => $this->faker->numberBetween(1, 99),
            'body' => $this->faker->realText(100),
            'validated_at' => $this->faker->date(),
        ];
    }
}
