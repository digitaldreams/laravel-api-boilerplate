<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| App\Models\User Factories
|--------------------------------------------------------------------------
*/

$factory->define(App\Models\User::class, function (Faker $faker) {
    return [
        "first_name" => $faker->firstName,
        "last_name" => $faker->lastName,
        "email" => $faker->safeEmail,
        'password' => bcrypt('123456'),
        "address" => $faker->address,
        "phone" => $faker->phoneNumber,
        "status" => 'pending',
        "email_verified_at" => $faker->dateTimeThisYear,
    ];
});
