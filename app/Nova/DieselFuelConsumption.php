<?php

namespace App\Nova;

use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Epartment\NovaDependencyContainer\NovaDependencyContainer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Markdown;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;

class DieselFuelConsumption extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\DieselFuelConsumption::class;

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
     * The logical group associated with the resource.
     *
     * @var string
     */
    public static $group = 'Manage';

    /**
     * Get the fields displayed by the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            Date::make('Date')
                ->rules(['required', 'date_format:Y-m-d'])
                ->withMeta([
                    'value' => now()->format('Y-m-d'),
                ])
                ->onlyOnForms(),

            Date::make('Date')
                ->sortable()
                ->exceptOnForms(),

            BelongsTo::make('Building', 'building', Building::class)
                ->rules(['required', 'exists:buildings,id'])
                ->withoutTrashed(),

            Select::make('Type')
                ->options(\App\DieselFuelConsumption::$type)
                ->rules(['required', Rule::in(array_keys(\App\DieselFuelConsumption::$type))])
                ->displayUsingLabels()
                ->onlyOnForms(),

            Select::make('Type')
                ->options(\App\DieselFuelConsumption::$type)
                ->exceptOnForms(),

            Text::make('Incoming', function () {
                return $this->type == 'incoming' ? "{$this->amount} liters" : '-';
            }),

            Text::make('Remain', function () {
                return $this->type == 'remain' ? "{$this->amount} liters" : '-';
            }),

            Text::make('Total Remain Fuel', function () {
                return "$this->total_remain liters";
            }),

            NovaDependencyContainer::make([
                Text::make('Incoming Amount (liters)', 'amount')
                    ->rules(['required', 'numeric', 'min:0']),
            ])->dependsOn('type', 'incoming')
            ->onlyOnForms(),

            NovaDependencyContainer::make([
                Text::make('Remain Amount (liters)', 'amount')
                    ->rules(['required', 'numeric', 'min:0']),
            ])->dependsOn('type', 'remain')
            ->onlyOnForms(),

            Markdown::make('Description')
                ->nullable()
                ->alwaysShow(),

            Markdown::make('Note')
                ->nullable()
                ->alwaysShow(),

            Images::make('Image'),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
