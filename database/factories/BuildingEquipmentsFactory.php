<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\BuildingEquipments;
use Faker\Generator as Faker;

$factory->define(BuildingEquipments::class, function (Faker $faker) {
    return [
        'number'                   => 'EQ-' . $faker->randomDigit,
        'desc'                     => $faker->sentence,
        'date_installation'        => now()->subDays(rand(1, 30)),
        'category'                 => $faker->sentence,
        'manufacture'              => $faker->sentence,
        'manufacture_model_number' => 'MMN-' . $faker->randomDigit,
        'year_of_construction'     => now()->subDays(rand(1, 30)),
        'costs_center'             => rand(10, 200) / 10,
        'location'                 => $faker->secondaryAddress,
        'barcode_number'           => $faker->uuid,
        'addtional_information'    => $faker->sentence,
    ];
});
