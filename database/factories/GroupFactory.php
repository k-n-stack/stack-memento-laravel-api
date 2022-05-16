<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;
use App\Models\User;

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
            'alphanumeric_id' => $this->generateANID(8),
            'owner_id' => rand(2, DB::table('users')->count()),
            'name' => substr($this->faker->catchPhrase(), 0, 32),
        ];
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
