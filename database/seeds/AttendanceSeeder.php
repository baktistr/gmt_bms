<?php

use App\Attendance;
use App\Building;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Building::with(['employees', 'manager'])
            ->get()
            ->each(function ($building) {
                for ($day = 1; $day <= 20; $day++) {
                    $building->employees->each(function ($employee) use ($building, $day) {
                        factory(Attendance::class)->create([
                            'building_id' => $building->id,
                            'employee_id' => $employee->id,
                            'date'        => now()->subDays($day),
                            'attendance'  => Arr::random(array_keys(Attendance::$types)),
                        ]);
                    });
                }
            });
    }
}
