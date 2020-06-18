<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Nova\Actions\Actionable;

class Attendance extends Model
{
    use SoftDeletes, Actionable;

    /**
     * Attribute casting.
     *
     * @var string[] $casts
     */
    protected $casts = [
        'date' => 'date'
    ];

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
     * Get formatted date attribute.
     *
     * @return mixed
     */
    public function getFormattedDateAttribute()
    {
        return $this->date->format('d F Y');
    }

    /**
     * A attendance BelongsTo building manager | user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class, 'building_id');
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
