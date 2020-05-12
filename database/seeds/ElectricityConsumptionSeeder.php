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
        Building::get()
            ->each(function ($building) {
                factory(ElectricityConsumption::class, rand(5, 10))->create([
                    'building_id' => $building->id,
                ]);
            });
    }
}
