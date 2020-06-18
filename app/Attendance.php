<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    public static $types = [
        'Hadir'     => 'hadir',
        'Izin'      => 'izin',
        'Alpha'     => 'alpha'
    ];

    /**
     * A attandance BelongsTo building manager | user
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function buildingManager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'building_manager_id');
    }

    /**
     * A attandance BelongsTo Employee
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
