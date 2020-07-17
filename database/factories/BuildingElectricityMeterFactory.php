<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Building;
use App\BuildingElectricityMeter;
use Faker\Generator as Faker;

$factory->define(BuildingElectricityMeter::class, function (Faker $faker) {
    return [
        'building_id' => function () {
            return factory(Building::class)->create()->id;
        },
        'name'        => "Meteran {$faker->numberBetween(1, 10)}",
        'desc'        => $faker->optional()->realText(),
    ];
});
