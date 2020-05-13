<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class WaterConsumption extends Model implements HasMedia
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




    /**
     * Get Total Cost
     *
     * @return int
     */
    public function totalUsage(): int
    {
        return $this->water_usage * $this->water_rate;
    }

    /**
     * formated Attribut water_usage
     *
     * @return mixed
     */
    public function getFormattedWaterUsageAttribute()
    {
        return number_format($this->water_usage) . ' M3';
    }

    /**
     * formated Attribut water_rate
     *
     * @return mixed
     */
    public function getFormattedWaterRateAttribute()
    {
        return 'Rp. ' . number_format($this->water_rate);
    }

    /**
     * formated Attribut totalUsage
     *
     * @return mixed
     */
    public function getFormattedTotalUsageAttribute()
    {
        return 'Rp. ' . number_format($this->totalUsage(), 2);
    }

    /**
     * Register the media collections.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('water_usage')
            ->singleFile();
    }
}
