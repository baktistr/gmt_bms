<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Building;
use App\Electricity;
use Faker\Generator as Faker;

$factory->define(Electricity::class, function (Faker $faker) {
    return [
        'building_id' => fn () => factory(Building::class)->create()->id,
        'lwbp'        => $faker->randomDigitNotNull,
        'lwbp_rate'   => $faker->randomDigitNotNull,
        'wbp'   => $faker->randomDigitNotNull,
        'wbp_rate'   => $faker->randomDigitNotNull,
        'kvr'   => $faker->randomDigitNotNull,
        'desc' => $faker->paragraph,
        'date' => now()
    ];
});
