<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| App\Models\Repayment Factories
|--------------------------------------------------------------------------
*/

$factory->define(App\Models\Repayment::class, function (Faker $faker) {
    return [
        "loan_id" => factory(\App\Models\Loan::class)->create()->id,
        "amount" => $faker->randomNumber(),
        "paid_at" => $faker->dateTimeThisYear(),
    ];
});
