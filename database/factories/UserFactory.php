<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Building;
use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;
use App\Testing\File;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/


$factory->define(User::class, function (Faker $faker) {
    return [
        'name'              => $faker->name,
        'building_id'       => rand(1, 3),
        'email'             => $faker->unique()->safeEmail,
        'phone_number'      => $faker->phoneNumber,
        'email_verified_at' => now(),
        'password'          => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token'    => Str::random(10),
    ];
});

$factory->state(User::class, 'super_admin', []);
$factory->state(User::class, 'building_manager', []);
$factory->state(User::class, 'help_desk', []);
$factory->state(User::class, 'viewer', []);

$factory->state(User::class, 'with_assigned_building', ['building_id' => function () {
    return factory(Building::class)->create()->id;
}]);

$factory->afterCreatingState(User::class, 'super_admin', function (User $user) {
    $user->assignRole('Super Admin');
});

$factory->afterCreatingState(User::class, 'building_manager', function (User $user) {
    $user->assignRole('Building Manager');
});

$factory->afterCreatingState(User::class, 'help_desk', function (User $user) {
    $user->assignRole('Help Desk');
});

$factory->afterCreatingState(User::class, 'viewer', function (User $user) {
    $user->assignRole('Viewer');
});

$factory->afterCreating(User::class, function (User $user) {
    // Add avatar image to factory.
    $user->addMedia(File::image("user-{$user->id}-avatar.png"))
        ->toMediaCollection('avatar');
});
