<?php

use App\Attendance;
use App\Employee;
use App\User;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $manager = User::role('Building Manager')->inRandomOrder()->first();
        Employee::get()->each(function ($employee) use ($manager) {
            factory(Attendance::class)->create([
                'building_manager_id'   => $manager->id,
                'employee_id'           => $employee->id
            ]);
        });
    }
}
