<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

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

    /**
     * A Building can Have Many electricity .
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function electricity(): HasMany
    {
        return $this->hasMany(Electricity::class, 'building_id');
    }
}
