<?php

use App\Building;
use Illuminate\Database\Seeder;
use App\User;

class BuildingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create buildings with unassigned viewers and help-desks
        factory(Building::class, 2)->create();

        // Create buildings with assigned viewers and help-desks
        factory(Building::class, rand(3, 5))->create()
            ->each(function ($building) {
                factory(User::class)->state('viewer')->create([
                    'building_id' => $building->id,
                ]);
                factory(User::class)->state('help_desk')->create([
                    'building_id' => $building->id,
                ]);
            });
    }
}
