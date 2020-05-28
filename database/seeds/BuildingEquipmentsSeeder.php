<?php

use App\BuildingEquipment;
use Illuminate\Database\Seeder;

class BuildingEquipmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(BuildingEquipment::class, 5)->create();
    }
}
