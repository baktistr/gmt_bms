<?php

use App\Building;
use App\Employee;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Building::get()
            ->each(function ($building) {
                factory(Employee::class, rand(3, 5))->create([
                    'building_id' => $building->id,
                ]);
            });
    }
}
