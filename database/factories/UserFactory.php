<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;

use Utils\Utils\generateANID;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $anid = $this->generateANID(8);
        $userPath = './storage/app/avatars/'.$anid;
        $imagesPath = './database/images';

        File::makeDirectory($userPath);

        $images = File::files($imagesPath);
        $images = array_map(function ($test) {
            return $test->getFileName();
        }, $images);
        $index = rand(0, count($images) - 1);
        $randomImagePath = $imagesPath.'/'.$images[$index];

        File::copy($randomImagePath, $userPath.'/'.$images[$index]);

        return [
            'alphanumeric_id' => $anid,
            'pseudonym' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'image_url' => '01234.png',
            'remember_token' => Str::random(10),
            'deleted_at' => null,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
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
