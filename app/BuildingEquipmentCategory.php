<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BuildingEquipmentCategory extends Model
{
    /**
     * An equipment category can have many equipments.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function equipments(): HasMany
    {
        return $this->hasMany(BuildingEquipment::class, 'building_equipment_category_id');
    }
}
