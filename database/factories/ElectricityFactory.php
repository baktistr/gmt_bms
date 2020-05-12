<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Building;
use App\Electricity;
use Faker\Generator as Faker;

$factory->define(Electricity::class, function (Faker $faker) {
    return [
        'building_id' => fn () => factory(Building::class)->create()->id,
        'lwbp'        => rand(100 , 500),
        'lwbp_rate'   => rand(100 , 500),
        'wbp'         => rand(100 , 500),
        'wbp_rate'    => rand(100 , 500),
        'kvr'         => rand(100 , 500),
        'desc'        => $faker->paragraph,
        'date'        => now()
    ];
});
