<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use Illuminate\Support\Facades\DB;
use App\Models\User;

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
            'poster_id' => rand(2, DB::table('users')->count()),
            'bookmark_id' => rand(1, DB::table('bookmarks')->count()),
            'body' => $this->faker->realText(100),
        ];
    }
}
