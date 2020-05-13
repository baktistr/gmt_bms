<?php

use App\WaterConsumption;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RoleAndPermissionSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(BuildingSeeder::class);
        $this->call(ElectricityConsumptionSeeder::class);
        $this->call(WaterConsumptionSeeder::class);
    }
}
