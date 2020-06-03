<?php

use Illuminate\Database\Seeder;

use App\BuildingEquipmentHistory;
use App\BuildingEquipment;

class BuildingEquipmentHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BuildingEquipment::get()->each(function ($equipment) {
            factory(BuildingEquipmentHistory::class, rand(3, 5))->create([
                'building_equipment_id' => $equipment->id,
            ]);
        });
    }
}
