<?php

use App\WaterConsumption;
use Illuminate\Database\Seeder;
use App\Building;

class WaterConsumptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get the generated buildings from other seeder and seed the electricity consumptions.
        Building::get()
            ->each(function ($building) {
                factory(WaterConsumption::class, rand(5, 10))->create([
                    'building_id' => $building->id,
                ]);
            });
    }
}
