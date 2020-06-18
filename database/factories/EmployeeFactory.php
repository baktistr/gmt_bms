<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Building;
use App\Employee;
use App\Testing\File;
use Faker\Generator as Faker;

$factory->define(Employee::class, function (Faker $faker) {
    return [
        'building_id' => function () {
            return factory(Building::class)->create()->id;
        },
        'name'        => $faker->name,
        'address'     => $faker->address,
        'position'    => $faker->sentence,
        'birth_place' => $faker->streetAddress,
        'birth_date'  => now()->subDays(3),
    ];
});

$factory->afterCreating(Employee::class, function (Employee $employee) {
    // Add avatar image to factory.
    $employee->addMedia(File::image("employee-{$employee->id}-avatar.png"))
        ->toMediaCollection('avatar');
});
