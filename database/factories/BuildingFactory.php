<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Building;
use App\User;
use Faker\Generator as Faker;

$factory->define(Building::class, function (Faker $faker) {
    return [
        'name'       => $faker->streetName,
        'location'   => $faker->locale,
        'manager_id' => function () {
            return factory(User::class)->state('manager')->create()->id;
        },
    ];
});
