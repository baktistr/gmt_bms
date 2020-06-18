<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Attendance;
use App\Building;
use App\Employee;
use Faker\Generator as Faker;

$factory->define(Attendance::class, function (Faker $faker) {
    return [
        'building_id' => function () {
            return factory(Building::class)->create()->id;
        },
        'employee_id' => function () {
            return factory(Employee::class)->create()->id;
        },
        'date'        => now()->subDays(rand(1, 30)),
        'attendance'  => $faker->randomElement(array_keys(Attendance::$types)),
        'desc'        => $faker->optional(0.2)->sentence,
    ];
});
