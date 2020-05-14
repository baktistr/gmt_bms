<?php

use App\Building;
use App\DieselFuelConsumption;
use Illuminate\Database\Seeder;

class DieselFuelConsumptionSeeder extends Seeder
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
                factory(DieselFuelConsumption::class)->create([
                    'building_id' => $building->id,
                    'type'        => 'incoming',
                    'date'        => now()->subDays(30),
                    'amount'      => 300,
                ]);

                factory(DieselFuelConsumption::class)->create([
                    'building_id' => $building->id,
                    'type'        => 'remain',
                    'date'        => now()->subDays(28),
                    'amount'      => 50,
                ]);

                factory(DieselFuelConsumption::class)->create([
                    'building_id' => $building->id,
                    'type'        => 'remain',
                    'date'        => now()->subDays(27),
                    'amount'      => 120,
                ]);

                factory(DieselFuelConsumption::class)->create([
                    'building_id' => $building->id,
                    'type'        => 'incoming',
                    'date'        => now()->subDays(25),
                    'amount'      => 100,
                ]);
            });
    }
}
