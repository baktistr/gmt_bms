<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Building;
use App\ElectricityConsumption;
use Faker\Generator as Faker;

$factory->define(ElectricityConsumption::class, function (Faker $faker) {
    return [
        'building_id' => function () {
            return factory(Building::class)->create()->id;
        },
        'date'        => now()->subDays(rand(1, 30)),
    ];
});
