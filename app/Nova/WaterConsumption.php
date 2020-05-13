<?php

namespace App\Nova;

use App\WaterConsumption as AppWaterConsumption;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Markdown;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;
use Rimu\FormattedNumber\FormattedNumber;

class WaterConsumption extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $group = 'Manage';


    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\WaterConsumption::class;

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
     * Searching With Relation
     *
     * @var Array
     */
    public static $with = [
        'building',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),



            FormattedNumber::make('Water Usage (m3)', 'water_usage')
                ->rules(['required', 'numeric'])
                ->onlyOnForms(),

            FormattedNumber::make('Water Rate (Rp)', 'water_rate')
                ->rules(['required', 'numeric'])
                ->onlyOnForms(),




            BelongsTo::make('Building', 'building', Building::class)
                ->rules(['required', 'exists:buildings,id'])
                ->hideFromIndex()
                ->withoutTrashed(),

            Date::make('Date', 'date')
                ->rules(['required', 'date_format:Y-m-d'])
                ->withMeta([
                    'value' => now()->format('Y-m-d'),
                ]),

            Text::make('Water Rate', function () {
                return $this->formatted_water_rate;
            })->exceptOnForms(),

            Text::make('Water Usage', function () {
                return $this->formatted_water_usage;
            })->exceptOnForms(),

            Text::make('Total Usage', function () {
                return $this->formatted_total_usage;
            })->exceptOnForms(),


            Markdown::make('Description', 'desc')
                ->nullable(),


            new Panel('Receipt of Payments', [
                Images::make('Water Payment Receipt', 'water_usage')
                    ->rules(['required'])
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
