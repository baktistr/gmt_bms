<?php

namespace App\Nova;

use App\Nova\Metrics\TotalBuildings;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Orlyapps\NovaBelongsToDepend\NovaBelongsToDepend;
use Outhebox\NovaHiddenField\HiddenField;

class Building extends Resource
{

    /**
     * The logical group associated with the resource.
     *
     * @var string
     */
    public static $group = 'Admin';

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Building::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'name',
        'location',
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
            return $query->where('manager_id', $user->id);
        }

        if (($user->hasRole('Help Desk') || $user->hasRole('Viewer')) && $user->building_id) {
            return $query->where('id', $user->building_id);
        }

        return $query;
    }

    /**
     * Search With Relation
     *
     * @var array
     */
    public static $with = [
        'manager',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            HiddenField::make('Admin', 'manager_id')
                ->defaultValue($request->user()->id)
                ->onlyOnForms(),

            BelongsTo::make('Manager', 'manager', User::class),

            NovaBelongsToDepend::make('Province')
                ->options(\App\Province::all())
                ->hideFromIndex(),

            NovaBelongsToDepend::make('Regency')
                ->optionsResolve(function ($province) {
                    return $province->regencies()->get(['id', 'name']);
                })
                ->dependsOn('Province')
                ->hideFromIndex(),

            NovaBelongsToDepend::make('District')
                ->optionsResolve(function ($regency) {
                    return $regency->districts()->get(['id', 'name']);
                })
                ->dependsOn('Regency')
                ->hideFromIndex(),

            Text::make('Name', 'name')
                ->rules('required')
                ->sortable(),

            Textarea::make('Address Detail')
                ->rules('required')
                ->onlyOnForms(),

            Text::make('Address Detail')
                ->exceptOnForms(),

            HasMany::make('Electricity Consumptions', 'electricityConsumptions', ElectricityConsumption::class),

            HasMany::make('Water Consumptions', 'waterConsumptions', WaterConsumption::class),

            HasMany::make('Diesel Fuel Consumptions', 'dieselFuelConsumptions', DieselFuelConsumption::class),

            HasMany::make('Equipments', 'equipments', BuildingEquipment::class),

            HasMany::make('Help Desks', 'helpDesks', User::class),

            HasMany::make('Viewers', 'viewers', User::class),
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
        return [
            (new TotalBuildings)->canSee(function () use ($request) {
                return $request->user()->hasRole('Super Admin');
            }),
        ];
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
