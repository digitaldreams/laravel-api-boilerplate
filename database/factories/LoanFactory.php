<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| App\Models\Loan Factories
|--------------------------------------------------------------------------
*/

$factory->define(App\Models\Loan::class, function (Faker $faker) {
    return [
        "user_id" => factory(\App\Models\User::class)->create()->id,
        "amount" => $faker->randomNumber(),
        "duration" => $faker->randomDigit,
        "interest_rate" => $faker->randomNumber(),
        "arrangement_fee" => $faker->randomNumber(),
        "status" => 'pending',

    ];
});
