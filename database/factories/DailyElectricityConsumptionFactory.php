<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\BuildingElectricityMeter;
use App\DailyElectricityConsumption;
use App\ElectricityConsumption;
use App\Testing\File;
use Faker\Generator as Faker;

$factory->define(DailyElectricityConsumption::class, function (Faker $faker) {
    return [
        'electricity_consumption_id' => function () {
            return factory(ElectricityConsumption::class)->create()->id;
        },
        'electricity_meter_id'       => function () {
            return factory(BuildingElectricityMeter::class)->create()->id;
        },
        'electric_meter'             => rand(100, 500),
        'lwbp'                       => rand(100, 500),
        'lwbp_rate'                  => rand(1000, 5000),
        'wbp'                        => rand(100, 500),
        'wbp_rate'                   => rand(1000, 5000),
        'kvar'                       => rand(100, 500),
        'description'                => $faker->sentence,
    ];
});

$factory->afterCreating(DailyElectricityConsumption::class, function (DailyElectricityConsumption $consumption) {
//    $consumption->addMedia(File::image("daily-electricity-consumption-wbp-{$consumption->id}.png"))
//        ->toMediaCollection('wbp');
//
//    $consumption->addMedia(File::image("daily-electricity-consumption-lwbp-{$consumption->id}.png"))
//        ->toMediaCollection('lwbp');
});

