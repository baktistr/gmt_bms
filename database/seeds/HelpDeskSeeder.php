<?php
use App\Building;
use App\HelpDesk;
use App\HelpDeskCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class HelpDeskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $randomCategory = HelpDeskCategory::pluck('id')->toArray();

        Building::get()->each(function ($building) use ($randomCategory) {
            factory(HelpDesk::class)->create([
                'user_id'               => $building->manager_id,
                'building_id'           => $building->id,
                'help_desk_category_id' => Arr::random($randomCategory),
            ]);
        });
    }
}
