<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ElectricityConsumption extends Model implements HasMedia
{
    use InteractsWithMedia;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'date' => 'datetime',
        'email_verified_at' => 'datetime',
    ];

    /**
     * A electricity belongTo building.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class, 'building_id');
    }

    public function electricityUsed(): int
    {
        return $this->lwbp + $this->wbp + $this->kvr;
    }


    public function totalCost(): int
    {
        return $this->lwbp_rate + $this->wbp_rate;
    }

    /**
     * Register the media collections.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('wbp')
            ->singleFile();

        $this->addMediaCollection('lwbp')
            ->singleFile();
    }
}
