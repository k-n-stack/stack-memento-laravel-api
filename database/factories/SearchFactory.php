<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class SearchFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $userCount = DB::table('users')->count();

        return [
            'user_id' => $this->faker->numberBetween(2, $userCount),
            'search_string' => $this->faker->realText(30),
        ];
    }
}
