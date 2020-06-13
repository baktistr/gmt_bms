<?php

use App\Building;
use App\HelpDesk;
use App\HelpDeskCategory;
use Illuminate\Database\Seeder;

class HelpDeskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Building::get()->each(function ($building) {
            factory(HelpDesk::class)->create([
                'user_id'               => $building->manager_id,
                'building_id'           => $building->id,
                'help_desk_category_id' => factory(HelpDeskCategory::class)->create()->id,
            ]);
        });
    }
}
