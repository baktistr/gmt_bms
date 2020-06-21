<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\DieselFuelConsumption;
use Laravel\Nova\Actions\Actionable;

class Building extends Model
{
    use SoftDeletes, Actionable;

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
        return $this->hasMany(DieselFuelConsumption::class, 'building_id');
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
     * A Building can Have Many electricity consumptions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function waterConsumptions(): HasMany
    {
        return $this->hasMany(WaterConsumption::class, 'building_id');
    }

    /**
     * A building can Have Many Employees
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class, 'building_id');
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
     * A building can have many equipment categories.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function equipmentCategories(): HasMany
    {
        return $this->hasMany(BuildingEquipmentCategory::class, 'building_id');
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
        return $this->hasMany(HelpDesk::class, 'building_id');
    }
}
