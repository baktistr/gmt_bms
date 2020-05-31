<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BuildingEquipmentHistory extends Model
{

    /**
     * {@inheritDoc}
     */
    protected $table = 'building_equipment_histories';

    /**
     * @var string[] $type
     */
    public static $type = [
        'corrective' => 'Corrective',
        'preventive' => 'Preventive',
    ];


    public function equipment(): BelongsTo
    {
        return $this->belongsTo(BuildingEquipment::class, 'building_equipment_id');
    }
}
