<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Markdown;

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
    public static $title = 'number';

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
                ->rules('required', 'string'),

            Date::make('Date Installation', 'date_installation')
                ->rules('required', 'date'),

            Text::make('Manufacture ', 'manufacture')
                ->rules('required', 'string'),

            Text::make('Manufacture Number ', 'manufacture_model_number')
                ->rules('required', 'string'),

            Text::make('Year Construction', 'year_of_construction')
                ->rules('required', 'min:1990', "max:" . date('Y'), 'numeric'),

            Text::make('Cost Center', 'costs_center')
                ->rules('required', 'string')
                ->onlyOnForms()
                ->showOnDetail(),

            Text::make('Location', 'location')
                ->rules('required', 'string')
                ->onlyOnForms()
                ->showOnDetail(),

            Text::make('Barcode Number', 'barcode_number')
                ->rules('required', 'string')
                ->onlyOnForms()
                ->showOnDetail(),

            Text::make('Addtional Information', 'addtional_information')
                ->rules('string')
                ->onlyOnForms()
                ->showOnDetail(),

            Markdown::make('Description', 'desc')
                ->rules('string')
                ->onlyOnForms()
                ->showOnDetail(),

            BelongsTo::make('category', 'category', BuildingEquipmentCategory::class)
                ->rules('required'),

            BelongsTo::make('Building', 'building', Building::class)
                ->rules('required'),

            HasMany::make('Histories', 'histories', BuildingEquipmentHistory::class)
                ->onlyOnIndex()
                ->onlyOnDetail(),
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
