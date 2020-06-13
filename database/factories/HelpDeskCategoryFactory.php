<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\HelpDeskCategory;
use Faker\Generator as Faker;

$factory->define(HelpDeskCategory::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
    ];
});
