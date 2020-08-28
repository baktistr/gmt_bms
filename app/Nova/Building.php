<?php

namespace App\Nova;

use App\Nova\Metrics\TotalBuildings;
use Coroowicaksono\ChartJsIntegration\LineChart;
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

            BelongsTo::make('Manager', 'manager', User::class)
                ->nullable()
                ->withoutTrashed(),

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

            Text::make('Phone Number', 'phone_number')
                ->rules('required')
                ->sortable(),

            Textarea::make('Address Detail')
                ->rules('required')
                ->onlyOnForms(),

            Text::make('Address Detail')
                ->exceptOnForms(),

            HasMany::make('Attendances', 'attendances', Attendance::class),

            HasMany::make('Employees', 'employees', Employee::class),

            HasMany::make('Meteran Gedung', 'buildingElectricityMeters', BuildingElectricityMeter::class),

            HasMany::make('Pemakaian Listrik Harian', 'electricityConsumptions', ElectricityConsumption::class),

            HasMany::make('Water Consumptions', 'waterConsumptions', WaterConsumption::class),

            HasMany::make('Diesel Fuel Consumptions', 'dieselFuelConsumptions', DieselFuelConsumption::class),

            HasMany::make('Equipments', 'equipments', BuildingEquipment::class),

            HasMany::make('Complaints', 'complaints', HelpDesk::class),

            HasMany::make('Employees', 'employees', Employee::class),

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
     * Add Monthly Chart
     * 
     * @author hanan
     * @return LineChart
     */
    protected function monthlyChart(Request $request)
    {
        // Collect the last 12 months.
        $months = collect([]);
        // Collect the series data.
        $seriesData = collect([]);

        $seriesData2 = collect([]);
        
        for ($month = 11; $month >= 0; $month--) {
            $months->push(now()->subMonths($month)->format('M Y'));
            $income = \App\DieselFuelConsumption::query()
                ->where('building_id', $request->get('resourceId'))
                ->where('type', 'incoming')
                ->whereYear('date', now()->subMonths($month)->format('Y'))
                ->whereMonth('date', now()->subMonths($month)->format('m'))
                ->sum('amount');

            $remain = \App\DieselFuelConsumption::query()
                ->where('type', 'remain')
                ->where('building_id', $request->get('resourceId'))
                ->whereYear('date', now()->subMonths($month)->format('Y'))
                ->whereMonth('date', now()->subMonths($month)->format('m'))
                ->sum('amount');

            $seriesData->push($income);

            $seriesData2->push($remain);
        }

        return (new LineChart())
            ->title('Total biaya Penggunaan Solar 12 bulan terakhir')
            ->animations([
                'enabled' => true,
                'easing'  => 'easeinout',
            ])
            ->series([
                [
                    'barPercentage' => 1,
                    'label'         => 'Stok Masuk',
                    'borderColor'   => '#2ecc71',
                    'data'          => $seriesData->toArray(),
                ],
                [
                    'barPercentage' => 1,
                    'label'         => 'Pemakaian Solar',
                    'borderColor'   => '#f1c40f',
                    'data'          => $seriesData2->toArray(),
                ],
            ])
            ->options([
                'xaxis' => [
                    'categories' => $months->toArray(),
                ],
            ])
            ->width('full')
            ->onlyOnDetail();
    }
}
