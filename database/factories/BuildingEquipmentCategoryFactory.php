<?php

/**
 * @var \Illuminate\Database\Eloquent\Factory $factory
 *
 * */

use App\Building;
use App\BuildingEquipmentCategory;
use Faker\Generator as Faker;

$factory->define(BuildingEquipmentCategory::class, function (Faker $faker) {
    return [
        'building_id' => function () {
            return factory(Building::class)->create()->id;
        },
        'name' => $faker->name,
    ];
});
