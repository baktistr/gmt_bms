<?php

use App\BuildingHelpDesk;
use App\BuildingHelpDeskCategory;
use Illuminate\Database\Seeder;

class HelpDeskCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $categories = collect([
            ['name' => 'Mechanical'],
            ['name' => 'Electrical '],
            ['name' => 'Furniture'],
            ['name' => 'Civil'],
            ['name' => 'Other'],
        ]);

        $categories->each(function ($category) {
            factory(BuildingHelpDeskCategory::class)->create([
                'name' => $category['name'],
            ]);
        });
    }
}
