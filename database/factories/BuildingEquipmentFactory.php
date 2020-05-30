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
        'equipment_number'               => 'EQ-' . $faker->randomNumber(5),
        'equipment_description'          => $faker->sentence,
        'date_installation'              => now()->subDays(rand(1, 30)),
        'manufacture'                    => $faker->company,
        'manufacture_model_number'       => 'MMN-' . $faker->randomNumber(5),
        'year_of_construction'           => rand(2000, 2019),
        'cost_center'                    => rand(1000000, 9000000),
        'location'                       => $faker->address,
        'barcode_number'                 => $faker->randomNumber(6),
        'additional_information'         => $faker->optional()->sentence,
    ];
});
