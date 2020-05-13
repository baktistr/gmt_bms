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

    /**
     * Get formatted LWBP gauge
     *
     * @return mixed
     */
    public function getFormattedLwbpGaugeAttribute()
    {
        return number_format($this->lwbp) . ' kwh';
    }

    /**
     * Get formatted WBP gauge
     *
     * @return mixed
     */
    public function getFormattedWbpGaugeAttribute()
    {
        return number_format($this->wbp) . ' kwh';
    }

    /**
     * Get formatted LWBP rate
     *
     * @return mixed
     */
    public function getFormattedLwbpRateAttribute()
    {
        return number_format($this->lwbp_rate) . ' kwh';
    }

    /**
     * Get formatted WBP rate
     *
     * @return mixed
     */
    public function getFormattedWbpRateAttribute()
    {
        return number_format($this->wbp_rate) . ' kwh';
    }

    /**
     * Get formatted total usage
     *
     * @return mixed
     */
    public function getFormattedTotalUsageAttribute()
    {
        return number_format($this->totalUsage()) . ' kwh';
    }

    /**
     * Get formatted total LWBP cost
     *
     * @return mixed
     */
    public function getFormattedLwbpCostAttribute()
    {
        return 'Rp. ' . number_format($this->totalLWBPCost(), 2);
    }

    /**
     * Get formatted total WBP cost
     *
     * @return mixed
     */
    public function getFormattedWbpCostAttribute()
    {
        return 'Rp. ' . number_format($this->totalWBPCost(), 2);
    }

    /**
     * Get formatted total cost
     *
     * @return mixed
     */
    public function getFormattedTotalCostAttribute()
    {
        return 'Rp. ' . number_format($this->totalCost(), 2);
    }

    /**
     * Get total usage (kwh).
     *
     * @return int
     */
    public function totalUsage(): int
    {
        return $this->lwbp + $this->wbp;
    }

    /**
     * Get total LWBP cost (Rp)
     *
     * @return int
     */
    public function totalLWBPCost(): int
    {
        return $this->lwbp * $this->lwbp_rate;
    }

    /**
     * Get total WBP cost (Rp)
     *
     * @return int
     */
    public function totalWBPCost(): int
    {
        return $this->wbp * $this->wbp_rate;
    }

    /**
     * Get total cost (Rp)
     *
     * @return int
     */
    public function totalCost(): int
    {
        return $this->totalLWBPCost() + $this->totalWBPCost();
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
