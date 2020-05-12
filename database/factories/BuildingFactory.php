<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Building;
use App\User;
use Faker\Generator as Faker;

$factory->define(Building::class, function (Faker $faker)  use ($factory) {
    return [
        'manager_id'   => function () {
            return factory(User::class)->state('building_manager')->create()->id;
        },
        'name'         => $faker->streetName,
        'location'     => $faker->locale,
        'phone_number' => $faker->phoneNumber,
    ];
});
