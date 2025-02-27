<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        User::all()->each(function ($user) use ($faker) {
            $user->update([
                'age' => $faker->numberBetween(18, 60),
                'latitude' => 42.6629,
                'longitude' => 21.1655,
            ]);
        });
    }
}
