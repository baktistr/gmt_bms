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
            $electricMeter = 100;

            for ($i = 1; $i <= rand(10, 30); $i++) {
                factory(ElectricityConsumption::class)->create([
                    'building_id'    => $building->id,
                    'date'           => now()->addDays($i),
                    'electric_meter' => $electricMeter += $usage = rand(100, 500),
                    'lwbp'           => $lwbp = rand(1, $usage),
                    'wbp'            => $usage - $lwbp,
                    'lwbp_rate'      => 1000,
                    'wbp_rate'       => 2000,
                ]);
            }
        });
    }
}
