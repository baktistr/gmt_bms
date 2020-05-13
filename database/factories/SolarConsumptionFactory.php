<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\SolarConsumption;
use Faker\Generator as Faker;

$factory->define(SolarConsumption::class, function (Faker $faker) {
    return [
        'solar_masuk' => random_int(100, 400),
        'sisa_solar' => random_int(0, 100),
        'informasi' => $faker->sentence,
        'catatan'  => $faker->paragraph,
    ];
});
