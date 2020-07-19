<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Building;
use App\HelpDesk;
use App\HelpDeskCategory;
use App\User;
use Faker\Generator as Faker;

$factory->define(HelpDesk::class, function (Faker $faker) {
    return [
        'help_desk_category_id' => function () {
            return factory(HelpDeskCategory::class)->create()->id;
        },
        'building_id'           => function () {
            return factory(Building::class)->create()->id;
        },
        'help_desk_id'          => function () {
            return factory(User::class)->create()->id;
        },
        'title'                 => $faker->sentence,
        'message'               => $faker->paragraph,
        'status'                => $faker->randomElement(array_keys(HelpDesk::$statuses)),
        'priority'              => $faker->randomElement(array_keys(HelpDesk::$priority)),
    ];
});
