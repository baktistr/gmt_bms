<?php

use App\Building;
use Illuminate\Database\Seeder;

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
        factory(Building::class , 2)->create();

        // Create buildings with assigned viewers and help-desks
        factory(Building::class , rand(3, 5))->create()
            ->each(function ($building) {
                // Seed the viewers and help-desks here.
            });
    }
}
