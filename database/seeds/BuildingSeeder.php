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
        $gedungs = file(database_path('seeds/building/gedung.csv'));
        foreach ($gedungs as $gedung) {
            $row = explode(';', $gedung);
            factory(Building::class)->create([
                'name' => $row[1],
                'location' => $row[2],
                'phone_number' => $row[3]
            ]);
        }

        // Create buildings with unassigned viewers and help-desks
        factory(Building::class, 2)->create();

        // Create buildings with assigned viewers and help-desks
        factory(Building::class, rand(5, 10))->create()
            ->each(function ($building) {
                factory(User::class)->state('viewer')->create(['building_id' => $building->id]);
                factory(User::class)->state('help_desk')->create(['building_id' => $building->id]);
            });
    }
}
