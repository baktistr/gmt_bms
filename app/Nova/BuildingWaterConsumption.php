<?php

namespace App\Nova;

use App\Statistics\Statistik;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Markdown;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Outhebox\NovaHiddenField\HiddenField;
use Rimu\FormattedNumber\FormattedNumber;

class BuildingWaterConsumption extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\BuildingWaterConsumption::class;

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
     * @var array
     */
    public static $with = [
        'building',
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
    public static $displayInNavigation = true;

    /**
     * Build an "index" query for the given resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function indexQuery(NovaRequest $request, $query)
    {
        $user = $request->user();

        if ($user->hasRole('Building Manager')) {
            return $query->whereHas('building', function ($q) use ($user) {
                return $q->where('manager_id', $user->id);
            });
        }

        if (($user->hasRole('Help Desk') || $user->hasRole('Viewer')) && $user->building_id) {
            return $query->where('building_id', $user->building_id);
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
            HiddenField::make('Building ID', 'building_id')
                ->defaultValue($request->user()->building->id ?? 0)
                ->onlyOnForms()
                ->canSee(function () use ($request) {
                    return !$request->user()->hasRole('Super Admin');
                }),

            Date::make('Date')
                ->rules(['required', 'date_format:Y-m-d'])
                ->withMeta([
                    'value' => now()->format('Y-m-d'),
                ])
                ->onlyOnForms(),

            Date::make('Date', function () {
                return $this->formatted_date;
            }),

            BelongsTo::make('Building', 'building', Building::class)
                ->rules(['required', 'exists:buildings,id'])
                ->withoutTrashed()
                ->sortable()
                ->canSee(function () use ($request) {
                    return $request->user()->hasRole('Super Admin');
                }),

            FormattedNumber::make('Usage (m3)', 'usage')
                ->rules(['required', 'numeric'])
                ->onlyOnForms(),

            FormattedNumber::make('Rate (Rp)', 'rate')
                ->rules(['required', 'numeric'])
                ->onlyOnForms(),

            Text::make('Usage', function () {
                return $this->formatted_usage;
            })->exceptOnForms(),

            Text::make('Rate', function () {
                return $this->formatted_rate;
            })->exceptOnForms(),

            Text::make('Total Usage', function () {
                return $this->formatted_total_usage;
            })->exceptOnForms(),

            Markdown::make('Description')
                ->nullable(),

            Images::make('Image', 'image')
                ->hideFromIndex(),
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
        $user = $request->user();

        if ($user->hasRole('Super Admin')) {
            // Super Admin
            $building = \App\BuildingWaterConsumption::where('id', $request->get('resourceId'))->first();
            return [
                (new Statistik($building->building_id ?? null))
                    ->monthlyWaterConsumptions()
                    ->onlyOnDetail(),
            ];
        }
        if (($user->hasRole('Help Desk') || $user->hasRole('Viewer')) || $user->hasRole('Building Manager') && $user->building_id) {
            return [
                (new Statistik($user->building_id))
                    ->monthlyWaterConsumptions(),
            ];
        } else {
            return [];
        }
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
        return 'Water Consumptions';
    }
}
