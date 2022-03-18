<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ThreadFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $visibilities = ['private', 'shareable', 'control', 'public'];

        return [
            'user_id' => $this->faker->numberBetween(1, 20),
            'title' => $this->faker->jobTitle(),
            'visibility' => $visibilities[rand(0, 3)],
            'image_url' => '0123456.png',
            'color' => sprintf('%06d', rand(0, 999999)),
        ];
    }
}
