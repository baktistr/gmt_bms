<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Building;
use App\User;
use Faker\Generator as Faker;

$factory->define(Building::class, function (Faker $faker) {
    return [
        'name' => $faker->streetName,
        'location' => $faker->locale,
        'admin_id' => fn () => factory(User::class)->state('manager')->create()->id
    ];
});
