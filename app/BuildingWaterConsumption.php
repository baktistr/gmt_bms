<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Nova\Actions\Actionable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class BuildingWaterConsumption extends Model implements HasMedia
{
    use InteractsWithMedia, Actionable, SoftDeletes;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'date' => 'date',
    ];

    /**
     * Get formatted date attribute.
     *
     * @return mixed
     */
    public function getFormattedDateAttribute()
    {
        return $this->date->format('d F Y');
    }

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
    public function totalUsage()
    {
        $usageYesterday = self::query()
            ->where('building_id', $this->building_id)
            ->where('date', date('Y-m-d', strtotime('-1 days', strtotime($this->date))))
            ->first();

        if ($usageYesterday) {
            return ($this->usage - $usageYesterday->usage) * $this->rate;
        }

        return $this->usage * $this->rate;
    }

    /**
     * Get formatter attribute water_usage
     *
     * @return mixed
     */
    public function getFormattedUsageAttribute()
    {
        return number_format($this->usage) . ' m3';
    }

    /**
     * Get formatter attribute water_rate
     *
     * @return mixed
     */
    public function getFormattedRateAttribute()
    {
        return 'Rp. ' . number_format($this->rate);
    }

    /**
     * Get formatter attribute totalUsage
     *
     * @return mixed
     */
    public function getFormattedTotalUsageAttribute()
    {
        return 'Rp. ' . number_format($this->totalUsage());
    }

    /**
     * Register the media collections.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')
            ->singleFile();
    }
}
