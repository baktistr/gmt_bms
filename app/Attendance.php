<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    /**
     * Attendance types.
     *
     * @var string[] $types
     */
    public static $types = [
        'hadir' => 'Hadir',
        'izin'  => 'Izin',
        'alpha' => 'Alpha'
    ];

    /**
     * A attendance BelongsTo building manager | user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function buildingManager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'building_manager_id');
    }

    /**
     * A attendance BelongsTo Employee
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
