<?php

use App\HelpDesk;
use App\HelpDeskCategory;
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
        factory(HelpDeskCategory::class, 2)->create();
    }
}
