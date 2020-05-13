<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\SolarConsumption;

class Building extends Model
{
    use SoftDeletes;

    /**
     * A building belongs to manager.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }


    public function solars(): HasMany
    {
        return $this->hasMany(SolarConsumption::class, 'building_id');
    }

    /**
     * A Building can Have Many electricity consumptions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function electricityConsumptions(): HasMany
    {
        return $this->hasMany(ElectricityConsumption::class, 'building_id');
    }

    /**
     * A building can assign many viewers.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function viewers(): HasMany
    {
        return $this->hasMany(User::class, 'building_id')
            ->whereHas('roles', function ($query) {
                $query->where('name', 'Viewer');
            });
    }

    /**
     * A building can assign many help-desks.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function helpDesks(): HasMany
    {
        return $this->hasMany(User::class, 'building_id')
            ->whereHas('roles', function ($query) {
                $query->where('name', 'Help Desk');
            });
    }
}
