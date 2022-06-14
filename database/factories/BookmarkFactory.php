<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BookmarkFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'url' => $this->faker->url(),
            'description' => $this->faker->realText(30),
            'validated_at' => $this->faker->date('Y-m-d H:i:s'),
        ];
    }
}
