<?php

use App\Building;
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
        Building::get()->each(function ($building) {
            $building->equipmentCategories()->get()
                ->each(function ($category) use ($building) {
                    factory(BuildingEquipment::class, rand(3, 5))->create([
                        'building_id'                    => $building->id,
                        'building_equipment_category_id' => $category->id,
                    ]);
                });
        });
    }
}
