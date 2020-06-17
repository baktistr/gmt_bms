<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Building;
use App\Employee;
use Faker\Generator as Faker;

$factory->define(Employee::class, function (Faker $faker) {
    return [
        'building_id'   => function () {
            return factory(Building::class)->create()->id;
        },
        'name'          => $faker->name,
        'address'       => $faker->address,
        'position'      => $faker->sentence,
        'place_birth'   => $faker->streetAddress,
        'birth_date'    => now()->subDays(3),
    ];
});
