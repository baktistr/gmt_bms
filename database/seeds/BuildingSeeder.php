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
        // Create Building from csv
        $buildings = file(database_path('seeds/data/buildings.csv'));

        foreach ($buildings as $building) {
            $row = explode(';', $building);

            factory(Building::class)->create([
                'name'         => $row[1],
                'location'     => $row[2],
                'phone_number' => $row[3],
            ]);
        }

        // Create buildings with assigned viewers and help-desks
        Building::get()->each(function ($building) {
            factory(User::class)->state('viewer')->create(['building_id' => $building->id]);
            factory(User::class)->state('help_desk')->create(['building_id' => $building->id]);
        });
    }
}
