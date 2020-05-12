<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Water extends Model
{
    /**
     * Cast Attributes date to datetime
     *
     * @var array
     */
    protected $cast = [
        'date' => 'datetime',
        'email_verified_at' => 'datetime',
    ];


}
