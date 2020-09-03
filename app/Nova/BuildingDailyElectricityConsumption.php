<?php

namespace App\Nova;

use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Markdown;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Panel;
use Rimu\FormattedNumber\FormattedNumber;

class BuildingDailyElectricityConsumption extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\BuildingDailyElectricityConsumption::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $group = 'Consumption';

    /**
     * Indicates if the resource should be displayed in the sidebar.
     *
     * @var bool
     */
    public static $displayInNavigation = false;

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        if ($request->has('viaResourceId')) {
            $electricityConsumption = \App\BuildingElectricityConsumption::find($request->get('viaResourceId'));

            if ($electricityConsumption) {
                $buildingMeter = \App\BuildingElectricityMeter::where('building_id', $electricityConsumption->building_id)
                    ->pluck('name', 'id');
            }
        } else {
            $electricityConsumption = $this->model()->elecelectricityConsumption;
        }

        return [
            BelongsTo::make('Pemakaian Harian Tanggal', 'electricityConsumption', BuildingElectricityConsumption::class)
                ->withoutTrashed(),

            BelongsTo::make('Meteran Gedung', 'buildingMeter', BuildingElectricityMeter::class)
                ->withoutTrashed()
                ->exceptOnForms(),

            Select::make('Meteran Gedung', 'building_electricity_meter_id')
                ->options($buildingMeter ?? [null => 'Belum input meteran gedung'])
                ->displayUsingLabels()
                ->rules([
                    'required',
                    Rule::unique('building_daily_electricity_consumptions', 'building_electricity_meter_id')
                        ->where(function ($query) use ($electricityConsumption) {
                            return $query->where('building_electricity_consumption_id', $electricityConsumption->id);
                        })
                        ->ignore($this->model()->id)
                ])
                ->onlyOnForms(),

            FormattedNumber::make('Electric Meter (KWh)', 'electric_meter')
                ->rules(['required', 'numeric'])
                ->onlyOnForms(),

            FormattedNumber::make('LWBP Gauge (KWh)', 'lwbp')
                ->rules(['required', 'numeric'])
                ->onlyOnForms(),

            FormattedNumber::make('LWBP Rate (Rp)', 'lwbp_rate')
                ->rules(['required', 'numeric'])
                ->onlyOnForms(),

            FormattedNumber::make('WBP Gauge (KWh)', 'wbp')
                ->rules(['required', 'numeric'])
                ->onlyOnForms(),

            FormattedNumber::make('WBP Rate (Rp)', 'wbp_rate')
                ->rules(['required', 'numeric'])
                ->onlyOnForms(),

            FormattedNumber::make('KVAr', 'kvar')
                ->rules(['nullable', 'numeric'])
                ->onlyOnForms(),

            Text::make('Electric Meter', function () {
                return $this->formatted_electric_meter;
            })->exceptOnForms(),

            Text::make('LWBP Gauge', function () {
                return $this->formatted_lwbp_gauge;
            })->exceptOnForms(),

            Text::make('LWBP Rate', function () {
                return $this->formatted_lwbp_rate;
            })->exceptOnForms(),

            Text::make('WBP Gauge', function () {
                return $this->formatted_wbp_gauge;
            })->exceptOnForms(),

            Text::make('WBP Rate', function () {
                return $this->formatted_wbp_rate;
            })->exceptOnForms(),

            Text::make('KVAr', function () {
                return $this->formatted_kvar;
            })->exceptOnForms(),

            Text::make('Total LWBP Cost', function () {
                return $this->formatted_lwbp_cost;
            })->hideFromIndex(),

            Text::make('Total WBP Cost', function () {
                return $this->formatted_wbp_cost;
            })->hideFromIndex(),

            Text::make('Total Cost Today', function () {
                return $this->formatted_total_cost;
            }),

            Markdown::make('Description')
                ->nullable()
                ->alwaysShow(),

            new Panel('Receipt of Payments', [
                Images::make('LWBP Payment Receipt', 'lwbp')
                    ->hideFromIndex(),

                Images::make('WBP Payment Receipt', 'wbp')
                    ->hideFromIndex(),
            ]),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
