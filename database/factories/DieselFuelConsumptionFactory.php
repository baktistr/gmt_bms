<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Building;
use App\DieselFuelConsumption;
use App\Testing\File;
use Faker\Generator as Faker;

$factory->define(DieselFuelConsumption::class, function (Faker $faker) {
    return [
        'building_id'  => function () {
            return factory(Building::class)->create()->id;
        },
        'type'         => $faker->randomElement(array_keys(DieselFuelConsumption::$type)),
        'date'         => $faker->date('Y-m-d'),
        'amount'       => rand(10, 100),
        'description'  => $faker->sentence,
        'note'         => $faker->paragraph,
    ];
});

$factory->afterCreating(DieselFuelConsumption::class, function (DieselFuelConsumption $consumption) {
    $consumption->addMedia(File::image("diesel-fuel-{$consumption->id}.png"))
        ->toMediaCollection('image');
});
