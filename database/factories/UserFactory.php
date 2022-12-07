<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = \Faker\Factory::create();

        return [
            'characterId' => $this->faker->numberBetween(100000,999999),
            'characterName' => $faker->name(),
            'ownerHash' => base64_encode(Str::random(16)),
            'token' => Str::random(64),
            'refreshToken' => base64_encode(Str::random(16)),
            'scopes' => ["esi-location.read_location.v1"],
            'rememberToken' => Str::random(32),
        ];
    }
}
