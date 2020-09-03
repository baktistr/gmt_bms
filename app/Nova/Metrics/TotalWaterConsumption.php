<?php

namespace App\Nova\Metrics;

use App\BuildingElectricityConsumption;
use App\BuildingWaterConsumption;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Value;

class TotalWaterConsumption extends Value
{
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        $totalConsumption = collect([]);

        BuildingWaterConsumption::query()
            ->where('building_id', $request->user()->building->id)
            ->get()
            ->each(function ($consumption) use ($totalConsumption) {
                $totalConsumption->add($consumption->usage);
            });

        return $this->result($totalConsumption->sum());
    }

    /**
     * Get the ranges available for the metric.
     *
     * @return array
     */
    public function ranges()
    {
        return [
            //
        ];
    }

    /**
     * Determine for how many minutes the metric should be cached.
     *
     * @return  \DateTimeInterface|\DateInterval|float|int
     */
    public function cacheFor()
    {
        //  return now()->addMinutes(5);
    }

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'total-water-consumption';
    }
}
