<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Employee extends Model
{

    protected $casts = [
        'birth_date' => 'date'
    ];

    /**
     * A Employee Belongs To Building
     */
    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class, 'building_id');
    }



    /**
     * Get formatted date attribute.
     *
     * @return mixed
     */
    public function getFormattedDateAttribute()
    {
        return $this->birth_date->format('d F Y');
    }
}
