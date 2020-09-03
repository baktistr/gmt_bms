<?php

namespace App\Nova;

use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;

class BuildingEmployee extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\BuildingEmployee::class;

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
        'name'
    ];

    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $group = 'Admin';

    /**
     * Indicates if the resource should be displayed in the sidebar.
     *
     * @var bool
     */
    public static $displayInNavigation = false;

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
            return $query->whereHas('building', function ($query) use ($user) {
                $query->where('manager_id', $user->id);
            });
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

            Images::make('Photo')
                ->conversionOnIndexView('small')
                ->conversionOnDetailView('large'),

            BelongsTo::make('Building', 'building', Building::class),

            Text::make('Name', 'name')
                ->rules('required', 'string'),

            Textarea::make('Address', 'address')
                ->rules('required', 'string')
                ->alwaysShow(),

            Text::make('Position', 'position')
                ->rules('required', 'string')
                ->hideFromIndex(),

            Text::make('Birth Place')
                ->rules('required', 'string')
                ->hideFromIndex(),

            Date::make('Birth Date')
                ->rules(['required', 'date_format:Y-m-d'])
                ->format('DD MMMM YYYY')
                ->hideFromIndex(),

            Text::make('Attendance Today', function () {
                return $this->attendance_today;
            }),

            HasMany::make('Attendances', 'attendances', BuildingEmployeeAttendance::class),
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
        return [
            new Actions\AddDailyAttendances,
        ];
    }
}
