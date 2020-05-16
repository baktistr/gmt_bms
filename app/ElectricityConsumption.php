<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Nova\Actions\Actionable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ElectricityConsumption extends Model implements HasMedia
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
     * Get formatted LWBP gauge
     *
     * @return mixed
     */
    public function getFormattedElectricMeterAttribute()
    {
        return number_format($this->electric_meter) . ' KWh';
    }

    /**
     * Get formatted LWBP gauge
     *
     * @return mixed
     */
    public function getFormattedLwbpGaugeAttribute()
    {
        return number_format($this->lwbp) . ' KWh';
    }

    /**
     * Get formatted WBP gauge
     *
     * @return mixed
     */
    public function getFormattedWbpGaugeAttribute()
    {
        return number_format($this->wbp) . ' KWh';
    }

    /**
     * Get formatted LWBP rate
     *
     * @return mixed
     */
    public function getFormattedLwbpRateAttribute()
    {
        return 'Rp. ' . number_format($this->lwbp_rate);
    }

    /**
     * Get formatted WBP rate
     *
     * @return mixed
     */
    public function getFormattedWbpRateAttribute()
    {
        return 'Rp. ' . number_format($this->wbp_rate);
    }

    /**
     * Get formatted KVAr rate
     *
     * @return mixed
     */
    public function getFormattedKvarAttribute()
    {
        return number_format($this->kvar) . ' KVAr';
    }

    /**
     * Get formatted total usage
     *
     * @return mixed
     */
    public function getFormattedTotalUsageAttribute()
    {
        return number_format($this->totalUsage()) . ' KWh';
    }

    /**
     * Get formatted total LWBP cost
     *
     * @return mixed
     */
    public function getFormattedLwbpCostAttribute()
    {
        return 'Rp. ' . number_format($this->totalLWBPCost());
    }

    /**
     * Get formatted total WBP cost
     *
     * @return mixed
     */
    public function getFormattedWbpCostAttribute()
    {
        return 'Rp. ' . number_format($this->totalWBPCost());
    }

    /**
     * Get formatted total cost
     *
     * @return mixed
     */
    public function getFormattedTotalCostAttribute()
    {
        return 'Rp. ' . number_format($this->totalCost());
    }

    /**
     * Get total usage (KWh).
     *
     * @return int
     */
    public function totalUsage(): int
    {
        $usageYesterday = self::query()
            ->where('building_id', $this->building_id)
            ->where('date', date('Y-m-d', strtotime('-1 days', strtotime($this->date))))
            ->first();

        if ($usageYesterday) {
            return ($this->electric_meter - $usageYesterday->electric_meter);
        }

        return $this->electric_meter;
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
