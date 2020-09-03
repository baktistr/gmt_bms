<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Nova\Actions\Actionable;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Building extends Model implements HasMedia
{
    use SoftDeletes, Actionable, InteractsWithMedia;

    /**
     * An asset belongs to location code.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

    /**
     * A building belongs to manager.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    /**
     * An asset belongs to province.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class, 'province_id');
    }

    /**
     * An asset belongs to regency.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function regency(): BelongsTo
    {
        return $this->belongsTo(Regency::class, 'regency_id');
    }

    /**
     * An asset belongs to district.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class, 'district_id');
    }

    /**
     * A building can have many diesel fuel consumptions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function dieselFuelConsumptions(): HasMany
    {
        return $this->hasMany(BuildingDieselFuelConsumption::class, 'building_id');
    }

    /**
     * A Building can Have Many electricity consumptions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function electricityConsumptions(): HasMany
    {
        return $this->hasMany(BuildingElectricityConsumption::class, 'building_id');
    }

    /**
     * A Building can Have Many electricity consumptions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function waterConsumptions(): HasMany
    {
        return $this->hasMany(BuildingWaterConsumption::class, 'building_id');
    }

    /**
     * A building can Have Many Employees
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function employees(): HasMany
    {
        return $this->hasMany(BuildingEmployee::class, 'building_id');
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
     * A building can have many equipments.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function equipments(): HasMany
    {
        return $this->hasMany(BuildingEquipment::class, 'building_id');
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

    /**
     * A building can have many complaints.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function complaints(): HasMany
    {
        return $this->hasMany(BuildingHelpDesk::class, 'building_id');
    }

    /**
     * A building can have many employee attendances.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(BuildingEmployeeAttendance::class, 'building_id');
    }

    /**
     * A building can have many building meters
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function buildingElectricityMeters(): HasMany
    {
        return $this->hasMany(BuildingElectricityMeter::class, 'building_id');
    }

    /**
     * Register the media collections
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')
            ->onlyKeepLatest(10)
            ->registerMediaConversions(function () {
                $this->addMediaConversion('thumbnail')
                    ->fit(Manipulations::FIT_CROP, 160, 105)
                    ->performOnCollections('image');
            });
    }
}
