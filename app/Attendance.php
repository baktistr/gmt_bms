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
     * Mass assigment attributes.
     *
     * @var string[] $fillable
     */
    protected $fillable = [
        'building_id',
        'date',
        'status',
        'desc',
    ];

    /**
     * Attribute casting.
     *
     * @var string[] $casts
     */
    protected $casts = [
        'date' => 'date'
    ];

    /**
     * Attendance statuses.
     *
     * @var string[] $statuses
     */
    public static $statuses = [
        'present' => 'Present',
        'permit'  => 'Permit',
        'absent'  => 'Absent'
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
