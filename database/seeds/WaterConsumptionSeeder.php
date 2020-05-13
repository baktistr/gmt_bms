<?php

use App\Building;
use App\WaterConsumption;
use Illuminate\Database\Seeder;

class WaterConsumptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Get the generated buildings from other seeder and seed the wate consumptions.
        Building::get()
            ->each(function ($building) {
                for ($i = 1; $i <= rand(10, 30); $i++) {
                    factory(WaterConsumption::class)->create([
                        'building_id' => $building->id,
                        'date'        => now()->subDays($i),
                    ]);
                }
            });
    }
}
