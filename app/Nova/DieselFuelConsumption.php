<?php

namespace App\Nova;

use Coroowicaksono\ChartJsIntegration\LineChart;
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

            Text::make('Usage', function () {
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
        return [
            $this->monthlyChart($request) ?? [],
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

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return 'Fuel Consumption';
    }

    /**
     * Add Monthly Chart
     * 
     * @author hanan
     */
    protected function monthlyChart(Request $request)
    {
        // Collect the last 12 months.
        $months = collect([]);
        // Collect the series data.
        $seriesData = collect([]);

        // $fuelInput = DieselFuelConsumption::query()
        if ($request->user()->hasRole('Building Manager')) {
            for ($month = 11; $month >= 0; $month--) {
                $months->push(now()->subMonths($month)->format('M Y'));
                $fuelInput = \App\DieselFuelConsumption::query()
                    ->where('building_id', $request->user()->building->id)
                    ->whereYear('date', now()->subMonths($month)->format('Y'))
                    ->whereMonth('date', now()->subMonths($month)->format('m'))
                    ->sum('amount');
                $seriesData->push($fuelInput);
            }
        } else {
            for ($month = 11; $month >= 0; $month--) {
                $months->push(now()->subMonths($month)->format('M Y'));
                $fuelInput = \App\DieselFuelConsumption::query()
                    ->whereYear('date', now()->subMonths($month)->format('Y'))
                    ->whereMonth('date', now()->subMonths($month)->format('m'))
                    ->sum('amount');
                $seriesData->push($fuelInput);
            }
        }


        return (new LineChart())
            ->title('Total biaya Penggunaan Solar 12 bulan terakhir')
            ->animations([
                'enabled' => true,
                'easing'  => 'easeinout',
            ])
            ->series([[
                'barPercentage' => 1,
                'label'         => 'Solar',
                'borderColor'   => '#f7a35c',
                'data'          => $seriesData->toArray(),
            ]])
            ->options([
                'xaxis' => [
                    'categories' => $months->toArray(),
                ],
            ])
            ->width('full');
    }
}
