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
        // Building::get()->each(function ($building) {
        //     factory(BuildingEquipmentCategory::class, rand(3, 5))->create([
        //         'building_id' => $building->id,
        //     ]);
        // });

        $categories = collect([
            ['name' => 'Mechanical'],
            ['name' => 'Electrical '],
            ['name' => 'Furniture'],
            ['name' => 'Other'],
        ]);

        $categories->each(function ($category) {
            factory(BuildingEquipmentCategory::class)->create([
                'name' => $category['name'],
            ]);
        });
    }
}
