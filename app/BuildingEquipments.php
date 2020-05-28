<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BuildingEquipment extends Model
{
    /**
     * The attributes that should be cast to native types.
     *
     *
     * @var array
     */
    protected $casts = [
        'year_of_construction' => 'date',
        'date_installation'    => 'date',
    ];
}
