<?php

namespace App\Nova;

use App\Nova\Metrics\TotalBuildings;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use GeneaLabs\NovaMapMarkerField\MapMarker;
use Coroowicaksono\ChartJsIntegration\LineChart;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;
use Outhebox\NovaHiddenField\HiddenField;
use Rimu\FormattedNumber\FormattedNumber;

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
        'building_code',
        'allotment',
        'phone_number',
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
        'area',
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

            Text::make('Kode Gedung', function () {
                return "{$this->area->code}-{$this->building_code}";
            }),

            Textarea::make('Deskripsi', 'description')
                ->rules('required')
                ->alwaysShow(),

            Textarea::make('Peruntukan', 'allotment')
                ->rules('required')
                ->alwaysShow(),

            Text::make('Telepon', 'phone_number')
                ->hideFromIndex(),

            Images::make('Gambar', 'image')
                ->rules(['required'])
                ->hideFromIndex(),

            new Panel('Detail Lokasi', [
                Text::make('TREG', function () {
                    return $this->area->regional->name;
                }),

                Text::make('Witel', function () {
                    return $this->area->witel->name;
                }),

                Text::make('Provinsi', function () {
                    return $this->area->provinsi->name ?? '—';
                }),

                Text::make('Kabupaten/Kota', function () {
                    return $this->area->kabupaten->name ?? '—';
                }),

                Text::make('Kecamatan', function () {
                    return $this->area->kecamatan->name ?? '—';
                }),

                Text::make('Alamat', function () {
                    return $this->area->address_detail ?? '—';
                }),

                Text::make('Kode Pos', function () {
                    return $this->area->postal_code ?? '-';
                }),

                FormattedNumber::make('Luas Tanah Total', 'surface_area')
                    ->help('satuan dalam m<sup>2</sup>')
                    ->onlyOnForms(),

                Text::make('Luas Tanah Total', function () {
                    return number_format($this->surface_area) . ' m<sup>2</sup>';
                })->asHtml(),

                MapMarker::make('Lokasi')
                    ->latitude('area.latitude')
                    ->longitude('area.longitude')
                    ->hideFromIndex()
                    ->exceptOnForms(),
            ]),

//            HasMany::make('Attendances', 'attendances', Attendance::class),
//
//            HasMany::make('Employees', 'employees', Employee::class),
//
//            HasMany::make('Meteran Gedung', 'buildingElectricityMeters', BuildingElectricityMeter::class),
//
//            HasMany::make('Pemakaian Listrik Harian', 'electricityConsumptions', ElectricityConsumption::class),
//
//            HasMany::make('Water Consumptions', 'waterConsumptions', WaterConsumption::class),
//
//            HasMany::make('Diesel Fuel Consumptions', 'dieselFuelConsumptions', DieselFuelConsumption::class),
//
//            HasMany::make('Equipments', 'equipments', BuildingEquipment::class),
//
//            HasMany::make('Complaints', 'complaints', HelpDesk::class),
//
//            HasMany::make('Employees', 'employees', Employee::class),
//
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

            $this->monthlyElectricityChart($request) ?? [],

            $this->monthlyChart($request) ?? [],

            $this->monthlyWaterConsumptions($request) ?? [],

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
            ->title('Rekap Pengunaan Solar')
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
                'tooltips' => [
                    'callbacks' => [
                        'label' => "function(tooltipItem, data) {	
                            // get the data label and data value to display	
                            // convert the data value to local string so it uses a comma seperated number	
                            var dataLabel = data.labels[tooltipItem.index];	
                            var value = ': ' + data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index].toLocaleString() + ' ltr';	
                            	
                            if (Chart.helpers.isArray(dataLabel)) {	
                                // show value on first line of multiline label	
                                // need to clone because we are changing the value	
                                dataLabel = dataLabel.slice();	
                                dataLabel[0] += value;	
                            } else {	
                                dataLabel += value;	
                            }	
                            	
                            // return the text to display on the tooltip	
                            return dataLabel;	
                        };"
                    ]
                ]
            ])
            ->width('full')
            ->onlyOnDetail();
    }

    /**
     * Chart Listrik
     * 
     * @param Request
     * @return LineChart
     * @author hanan
     */
    protected function monthlyElectricityChart(Request $request)
    {
        // Collect the last 12 months.
        $months = collect([]);
        // Collect the series data.
        $seriesData = collect([]);
        for ($month = 11; $month >= 0; $month--) {
            $months->push(now()->subMonths($month)->format('M Y'));
            $listrik = \App\ElectricityConsumption::query()
                ->where('building_id', $request->get('resourceId'))
                ->whereMonth('date', now()->subMonths($month)->format('m'))
                ->get();
            $totalCost = $listrik->map(fn ($cost) => $cost->totalCost());
            $seriesData->push($totalCost);
        }

        $totalCost = collect([]);

        for ($i = 0; $i < count($seriesData); $i++) {
            $totalCost->push($seriesData[$i]->sum());
        }

        return (new LineChart())
            ->title('Rekap Penggunaan Listrik')
            ->animations([
                'enabled' => true,
                'easing'  => 'easeinout',
            ])
            ->series([
                [
                    'barPercentage' => 1,
                    'label'         => 'Penggunaan Listrik',
                    'borderColor'   => '#3498db',
                    'data'          => $totalCost->toArray(),
                ],
            ])
            ->options([
                'xaxis' => [
                    'categories' => $months->toArray(),
                ],
                'tooltips' => [
                    'callbacks' => [
                        'label' => "function(tooltipItem, data) {	
                            // get the data label and data value to display	
                            // convert the data value to local string so it uses a comma seperated number	
                            var dataLabel = data.labels[tooltipItem.index];	
                            var value = ': Rp.' + data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index].toLocaleString();	
                            	
                            if (Chart.helpers.isArray(dataLabel)) {	
                                // show value on first line of multiline label	
                                // need to clone because we are changing the value	
                                dataLabel = dataLabel.slice();	
                                dataLabel[0] += value;	
                            } else {	
                                dataLabel += value;	
                            }	
                            	
                            // return the text to display on the tooltip	
                            return dataLabel;	
                        };"
                    ]
                ]
            ])
            ->width('full')
            ->onlyOnDetail();
    }

    /**
     * Water Consumption Chart
     * 
     * @param $request
     * @author hanan
     * @return LineChart
     */
    protected function monthlyWaterConsumptions(Request $request)
    {
        $months = collect([]);
        $waterSeries = collect([]);

        for ($month = 11; $month >= 0; $month--) {
            $months->add(now()->subMonths($month)->format('M Y'));

            $waterConsumption = \App\WaterConsumption::query()
                ->where('building_id', $request->get('resourceId'))
                ->whereMonth('date', now()->subMonths($month)->format('m'))
                ->get();

            $waterSeries->push($waterConsumption->sum('usage'));
        }

        return (new LineChart())
            ->title('Rekap Pemakaian Air')
            ->animations([
                'enabled' => true,
                'easing'  => 'easeinout',
            ])
            ->series([
                [
                    'barPercentage' => 1,
                    'label'         => 'Pemakaian Air',
                    'borderColor'   => '#0077be',
                    'data'          => $waterSeries->toArray(),
                ],
            ])
            ->options([
                'xaxis' => [
                    'categories' => $months->toArray(),
                ],
                'tooltips' => [
                    'callbacks' => [
                        'label' => "function(tooltipItem, data) {	
                        // get the data label and data value to display	
                        // convert the data value to local string so it uses a comma seperated number	
                        var dataLabel = data.labels[tooltipItem.index];	
                        var value = ': ' + data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index].toLocaleString() + ' m3';	
                            
                        if (Chart.helpers.isArray(dataLabel)) {	
                            // show value on first line of multiline label	
                            // need to clone because we are changing the value	
                            dataLabel = dataLabel.slice();	
                            dataLabel[0] += value;	
                        } else {	
                            dataLabel += value;	
                        }	
                            
                        // return the text to display on the tooltip	
                        return dataLabel;	
                    };"
                    ]
                ]
            ])
            ->width('full')
            ->onlyOnDetail();
    }
}
