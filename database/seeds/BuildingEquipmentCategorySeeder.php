<?php

use App\Building;
use App\BuildingEquipmentCategory;
use Illuminate\Database\Seeder;

class BuildingEquipmentCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Building::get()->each(function ($building) {
            factory(BuildingEquipmentCategory::class)->create([
                'building_id' => $building->id,
            ]);
        });
    }
}
