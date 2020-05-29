<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Date;

class BuildingEquipment extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $group = 'Consumptions';

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\BuildingEquipment::class;

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
        'number',
        'manufacture'
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

            Text::make('Number', 'number')
                ->rules('required|string'),

            Date::make('Date Installation', 'date_installation')
                ->rules('required|date'),

            Text::make('Manufacture ', 'manufacture')
                ->rules('required|string'),

            Text::make('Manufacture Number ', 'manufacture_model_number')
                ->rules('required|string'),

            Date::make('Year Construction', 'year_of_construction')
                ->rules('required|date'),

            Text::make('Cost Center', 'costs_center')
                ->rules('required|string')
                ->onlyOnForms()
                ->showOnDetail(),

            Text::make('Location', 'location')
                ->rules('required|string')
                ->onlyOnForms()
                ->showOnDetail(),

            Text::make('Barcode Number', 'barcode_number')
                ->rules('required')
                ->onlyOnForms()
                ->showOnDetail(),

            Text::make('Addtional Information', 'addtional_information')
                ->onlyOnForms()
                ->showOnDetail(),

            BelongsTo::make('category', 'category', BuildingEquipmentCategory::class)
                ->rules('required|exists:building_equipment_categories'),
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
