<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\BuildingEquipment;
use App\BuildingEquipmentCategory;
use Faker\Generator as Faker;

$factory->define(BuildingEquipment::class, function (Faker $faker) {
    return [
        'building_equipment_category_id'    => rand(1, 5),
        'number'                            => 'EQ-' . $faker->randomDigit,
        'desc'                              => $faker->sentence,
        'date_installation'                 => now()->subDays(rand(1, 30)),
        'manufacture'                       => $faker->sentence,
        'manufacture_model_number'          => 'MMN-' . $faker->randomDigit,
        'year_of_construction'              => now()->subDays(rand(1, 30)),
        'costs_center'                      => rand(10, 200) / 10,
        'location'                          => $faker->secondaryAddress,
        'barcode_number'                    => $faker->uuid,
        'addtional_information'             => $faker->sentence,
    ];
});
