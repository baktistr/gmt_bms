<?php

use App\Building;
use App\Electricity;
use Illuminate\Database\Seeder;

class ElectricitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Electricity::class, 5)->create();
    }
}
