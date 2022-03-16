<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class FriendFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'friend_id' => 100,
            'user_id' => $this->faker->numberBetween(1, 99),
            'validated_at' => $this->faker->date(),
        ];
    }
}
