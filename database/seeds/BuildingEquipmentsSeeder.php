<?php

use App\BuildingEquipment;
use App\BuildingEquipmentCategory;
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
        BuildingEquipmentCategory::get()->each(function ($category) {
            factory(BuildingEquipment::class)->create([
                'building_equipment_category_id' => $category->id,
            ]);
        });
    }
}
