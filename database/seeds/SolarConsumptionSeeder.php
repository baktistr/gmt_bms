<?php

use App\Building;
use App\SolarConsumption;
use Illuminate\Database\Seeder;

class SolarConsumptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Building::get()->each(fn ($building) =>
        factory(SolarConsumption::class)->create(['building_id' => $building->id]));
    }
}
