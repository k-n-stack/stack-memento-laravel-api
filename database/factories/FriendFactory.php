<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class FriendFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        $exits = true;
        $randomIds = null;

        while ($exits) {
            echo 'here';
            $randomIds = $this->getRandomDistinctIds();
            $exits = DB::table('friends')
                ->where('user_id', $randomIds['user'])
                ->where('friend_id', $randomIds['friend'])
                ->exists();
            echo $exits;
        }

        return [
            'user_id' => $randomIds['user'],
            'friend_id' => $randomIds['friend'],
            'validated_at' => $this->faker->date(),
        ];
    }

    public function getRandomDistinctIds () {

        $usersCount = DB::table('users')->count();
        do {
            $randomIds = [
                'user' => rand(2, $usersCount), // 2 : impossible to be friend with Global
                'friend' => rand(2, $usersCount),
            ];
        } while (count(array_unique($randomIds)) == 1);

        return $randomIds;

    }

}
