<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Attendance;
use App\Employee;
use App\User;
use Faker\Generator as Faker;

$factory->define(Attendance::class, function (Faker $faker) {
    return [
        'building_manager_id'   => function () {
            return factory(User::class)->state('building_manager')->create()->id;
        },
        'employee_id'           => function () {
            return factory(Employee::class)->create()->id;
        },
        'attandance'            => $faker->randomElement(array_keys(Attendance::$types)),
        'desc'                  => $faker->sentence,
        'date'                  => now()->subDays(rand(5, 3)),
    ];
});
