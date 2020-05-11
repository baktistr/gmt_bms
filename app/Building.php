<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Building extends Model
{
    use SoftDeletes;

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }
}
