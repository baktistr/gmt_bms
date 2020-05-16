<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Building;
use App\District;
use App\Province;
use App\Regency;
use App\User;
use Faker\Generator as Faker;

$factory->define(Building::class, function (Faker $faker) use ($factory) {
    return [
        'manager_id'     => function () {
            return factory(User::class)->state('building_manager')->create()->id;
        },
        'province_id'    => function () {
            return factory(Province::class)->create()->id;
        },
        'regency_id'     => function () {
            return factory(Regency::class)->create()->id;
        },
        'district_id'    => function () {
            return factory(District::class)->create()->id;
        },
        'name'           => $faker->streetName,
        'address_detail' => $faker->streetAddress,
        'phone_number'   => $faker->phoneNumber,
    ];
});
