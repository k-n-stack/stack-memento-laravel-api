<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class GroupFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'owner_id' => $this->faker->numberBetween(1, 99),
            'name' => $this->faker->catchPhrase(),
            'image_url' => $this->faker->url(),
        ];
    }
}
