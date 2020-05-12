<?php

use Illuminate\Database\Seeder;
use App\Water;

class WaterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Water::class, 5)->create();
    }
}
