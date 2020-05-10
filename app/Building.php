<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    //
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id', 'id');
    }
}
