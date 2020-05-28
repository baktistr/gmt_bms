<?php

use App\BuildingEquipments;
use Illuminate\Database\Seeder;

class BuildingEquipmentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(BuildingEquipments::class, 5)->create();
    }
}
