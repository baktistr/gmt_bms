<?php

use App\BuildingEmployeeAttendance;
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
//        $this->call(RoleAndPermissionSeeder::class);
//        $this->call(UserSeeder::class);
//        $this->call(ProvinceSeeder::class);
//        $this->call(RegencySeeder::class);
//        $this->call(DistrictSeeder::class);
//        $this->call(BuildingSeeder::class);
//        $this->call(BuildingElectricityMeterSeeder::class);
//        $this->call(WaterConsumptionSeeder::class);
//        $this->call(DieselFuelConsumptionSeeder::class);
        $this->call(BuildingEquipmentCategorySeeder::class);
        $this->call(BuildingEquipmentSeeder::class);
        $this->call(BuildingEquipmentHistorySeeder::class);
        $this->call(HelpDeskCategorySeeder::class);
        $this->call(HelpDeskSeeder::class);
        $this->call(EmployeeSeeder::class);
        $this->call(AttendanceSeeder::class);

    }
}
