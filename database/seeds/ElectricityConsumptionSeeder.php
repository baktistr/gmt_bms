<?php

use App\Building;
use App\ElectricityConsumption;
use Illuminate\Database\Seeder;

class ElectricityConsumptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get the generated buildings from other seeder and seed the electricity consumptions.
        Building::get()->each(function ($building) {
            for ($i = 1; $i <= 5; $i++) {
                factory(ElectricityConsumption::class)->create([
                    'building_id' => $building->id,
                    'date'        => now()->addDays($i),
                ]);
            }
        });
    }
}
