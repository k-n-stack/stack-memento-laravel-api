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
            'alphanumeric_id' => $this->generateANID(8),
            'user_id' => $this->faker->numberBetween(1, 20),
            'title' => $this->faker->jobTitle(),
            'visibility' => $visibilities[rand(0, 3)],
            'image_url' => '0123456.png',
            'color' => sprintf('%06d', rand(0, 999999)),
        ];
    }

    public function forGlobal()
    {
        return $this->state(function (array $attributes) {
            return [
                'user_id' => '1',
            ];
        });
    }

    public function generateANID($length) {
        $alphaNumerics = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
        $random = "";
        for ($i = 0; $i < $length; $i++) {
            $random .= $alphaNumerics[rand(0, strlen($alphaNumerics) - 1)];
        }
        return $random;
    }
}
