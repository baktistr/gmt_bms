<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Building;
use App\ElectricityConsumption;
use App\Testing\File;
use Faker\Generator as Faker;

$factory->define(ElectricityConsumption::class, function (Faker $faker) {
    return [
        'building_id' => function () {
            return factory(Building::class)->create()->id;
        },
        'date'        => now()->subDays(rand(1, 30)),
        'lwbp'        => rand(100, 500),
        'lwbp_rate'   => rand(1000, 5000),
        'wbp'         => rand(100, 500),
        'wbp_rate'    => rand(1000, 5000),
        'kvar'        => rand(100, 500),
        'description' => $faker->sentence,
    ];
});

$factory->afterCreating(ElectricityConsumption::class, function (ElectricityConsumption $consumption) {
    $consumption->addMedia(File::image("electricity-consumption-wbp-{$consumption->id}.png"))
        ->toMediaCollection('wbp');

    $consumption->addMedia(File::image("electricity-consumption-lwbp-{$consumption->id}.png"))
        ->toMediaCollection('lwbp');
});
