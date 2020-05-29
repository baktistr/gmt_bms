<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Building;
use App\BuildingEquipment;
use App\BuildingEquipmentCategory;
use Faker\Generator as Faker;

$factory->define(BuildingEquipment::class, function (Faker $faker) {
    return [
        'building_id'                    => function () {
            return factory(Building::class)->create()->id;
        },
        'building_equipment_category_id' => function () {
            return factory(BuildingEquipmentCategory::class)->create()->id;
        },
        'number'                         => 'EQ-' . $faker->randomDigit,
        'desc'                           => $faker->sentence,
        'date_installation'              => now()->subDays(rand(1, 30)),
        'manufacture'                    => $faker->sentence,
        'manufacture_model_number'       => 'MMN-' . $faker->randomDigit,
        'year_of_construction'           => rand(2000, 2019),
        'costs_center'                   => rand(1000000, 9000000),
        'location'                       => $faker->address,
        'barcode_number'                 => $faker->randomNumber(6),
        'addtional_information'          => $faker->optional()->sentence,
    ];
});
