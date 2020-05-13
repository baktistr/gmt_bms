<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Building;
use App\WaterConsumption;
use Faker\Generator as Faker;

$factory->define(WaterConsumption::class, function (Faker $faker) {
    return [
        'building_id' => fn () => factory(Building::class)->create()->id,
        'water_usage' => rand(100, 1000),
        'water_rate'  => rand(1000, 8000),
        'desc'        => $faker->sentence,
        'date'        => now(),
    ];
});
