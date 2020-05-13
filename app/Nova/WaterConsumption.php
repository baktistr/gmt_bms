<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Markdown;
use Laravel\Nova\Http\Requests\NovaRequest;
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

            BelongsTo::make('Building', 'building', Building::class)
                ->rules(['required', 'exists:buildings,id'])
                ->withoutTrashed(),

            Date::make('Date', 'date')
                ->rules(['required', 'date_format:Y-m-d'])
                ->withMeta([
                    'value' => now()->format('Y-m-d'),
                ]),

            FormattedNumber::make('Water Usage (m3)', 'water_usage')
                ->rules(['required', 'numeric']),

            FormattedNumber::make('Water Rate (Rp)', 'water_rate')
                ->rules(['required', 'numeric']),

            Markdown::make('Description', 'desc')
                ->nullable(),

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
