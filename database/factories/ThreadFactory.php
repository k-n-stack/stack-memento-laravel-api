<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;
use App\Models\User;

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
        $userCount = DB::table('users')->count();

        return [
            'alphanumeric_id' => $this->generateANID(8),
            'user_id' => $this->faker->numberBetween(2, $userCount),
            'title' => substr($this->faker->jobTitle(), 0, 32),
            'visibility' => $visibilities[rand(0, 3)],
            'color' => sprintf('%06d', rand(0, 999999)),
        ];
    }

    public function forGlobal($title, $color)
    {
        return $this->state(function (array $attributes) use ($title, $color) {
            return [
                'user_id' => '1',
                'title' => $title,
                'color' => $color,
                'visibility' => 'public',
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
