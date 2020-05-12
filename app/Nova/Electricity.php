<?php

namespace App\Nova;

use App\Electricity as AppElectricity;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class Electricity extends Resource
{

    /**
     * The logical group associated with the resource.
     *
     * @var string
     */
    public static $group = 'Manage';

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Electricity::class;

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
     * Search With Relastion
     *
     * @var array
     */
    public static $with = [
        'building'
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

            DateTime::make('date')
                ->format('DD/MM/YYYY')
                ->firstDayOfWeek(1),

            Number::make('LWBP')->sortable(),

            Number::make('LWBP_RATE')
                ->sortable(),

            Number::make('WBP')->sortable(),

            Number::make('WBP_RATE')
                ->sortable(),

            Number::make('KVR')->sortable(),

            Number::make('Today Used', function (AppElectricity $electricity) {
                return $electricity->electricityUsed();
            })->onlyOnIndex(),

            Number::make('Total Cost', function (AppElectricity $electricity) {
                return $electricity->totalCost();
            })->onlyOnIndex(),

            Textarea::make('desc')
                ->sortable()
                ->hideFromIndex(),

            BelongsTo::make('Building', 'building', Building::class)
                ->onlyOnForms()
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
