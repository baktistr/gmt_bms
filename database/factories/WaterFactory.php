<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Building;
use App\Water;
use Faker\Generator as Faker;

$factory->define(Water::class, function (Faker $faker) {
    return [
        'building_id' => fn () => factory(Building::class)->create()->id,
        'water_usage' => rand(100, 500),
        'water_rate'  => rand(100, 500),
        'desc'        => $faker->paragraph,
        'date'        => now()
    ];
});
