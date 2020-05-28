<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    /**
     * The attributes that should be cast to native types.
     *
     * Casting year_of_construction to date format
     *
     * @var array
     */
    protected $casts = [
        'year_of_construction' => 'date',
        'date_installation'    => 'date'
    ];
}
