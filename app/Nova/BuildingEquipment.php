<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Markdown;

class BuildingEquipment extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $group = 'Equipments';

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
     * Build an "index" query for the given resource.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     * @param \Illuminate\Database\Eloquent\Builder   $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function indexQuery(NovaRequest $request, $query)
    {
        $user = $request->user();

        if ($user->hasRole('Building Manager')) {
            return $query->where('building_id', $user->building->id);
        }

        return $query;
    }

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

            BelongsTo::make('Category', 'category', BuildingEquipmentCategory::class)
                ->rules('required'),

            BelongsTo::make('Building', 'building', Building::class)
                ->rules('required'),

            Text::make('Equipment Number')
                ->rules('required', 'string'),

            Textarea::make('Equipment Description')
                ->rules('string')
                ->alwaysShow(),

            Date::make('Date Installation')
                ->rules(['required', 'date_format:Y-m-d'])
                ->withMeta([
                    'value' => now()->format('Y-m-d'),
                ])
                ->onlyOnForms(),

            Date::make('Date Installation', function () {
                return $this->formatted_date;
            }),

            Text::make('Manufacture')
                ->rules('required', 'string'),

            Text::make('Manufacture Model Number')
                ->rules('required', 'string'),

            Text::make('Year of Construction')
                ->rules('required', 'numeric', 'min:1990', 'max:' . date('Y')),

            Text::make('Cost Center')
                ->rules('required'),

            Textarea::make('Location')
                ->rules('required')
                ->alwaysShow(),

            Text::make('Barcode Number')
                ->rules('required')
                ->onlyOnForms()
                ->showOnDetail(),

            Text::make('Additional Information')
                ->rules('string')
                ->onlyOnForms()
                ->showOnDetail(),
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

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return 'Equipments';
    }
}
