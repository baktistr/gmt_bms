<?php

namespace App\Nova;

use App\ElectricityConsumption as AppElectricity;
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

class ElectricityConsumption extends Resource
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
    public static $model = \App\ElectricityConsumption::class;

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
     * Search With relations
     *
     * @var array
     */
    public static $with = [
        'building',
    ];

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

            Date::make('Date', function () {
                return $this->formatted_date;
            }),

            BelongsTo::make('Building', 'building', Building::class)
                ->rules(['required', 'exists:buildings,id'])
                ->withoutTrashed(),

            FormattedNumber::make('LWBP Gauge (kwh)', 'lwbp')
                ->rules(['required', 'numeric'])
                ->onlyOnForms(),

            FormattedNumber::make('LWBP Rate (Rp)', 'lwbp_rate')
                ->rules(['required', 'numeric'])
                ->onlyOnForms(),

            FormattedNumber::make('WBP Gauge (kwh)', 'wbp')
                ->rules(['required', 'numeric'])
                ->onlyOnForms(),

            FormattedNumber::make('WBP Rate (Rp)', 'wbp_rate')
                ->rules(['required', 'numeric'])
                ->onlyOnForms(),

            FormattedNumber::make('KVAR (kvar)', 'kvar')
                ->rules(['required', 'numeric'])
                ->onlyOnForms(),

            Text::make('LWBP Gauge', function () {
                return $this->formatted_lwbp_gauge;
            })->exceptOnForms(),

            Text::make('LWBP Rate', function () {
                return $this->formatted_lwbp_rate;
            })->exceptOnForms(),

            Text::make('WBP Gauge', function () {
                return $this->formatted_wbp_gauge;
            })->exceptOnForms(),

            Text::make('WBP Rate', function () {
                return $this->formatted_wbp_rate;
            })->exceptOnForms(),

            Text::make('Total Usage', function () {
                return $this->formatted_total_usage;
            }),

            Text::make('Total LWBP Cost', function () {
                return $this->formatted_lwbp_cost;
            })->hideFromIndex(),

            Text::make('Total WBP Cost', function () {
                return $this->formatted_wbp_cost;
            })->hideFromIndex(),

            Text::make('Total Cost', function () {
                return $this->formatted_total_cost;
            }),

            Markdown::make('Description')
                ->nullable()
                ->alwaysShow(),

            new Panel('Receipt of Payments', [
                Images::make('LWBP Payment Receipt', 'lwbp')
                    ->rules(['required'])
                    ->hideFromIndex(),

                Images::make('WBP Payment Receipt', 'wbp')
                    ->rules(['required'])
                    ->hideFromIndex(),
            ]),
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
