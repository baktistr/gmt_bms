<?php

namespace App\Nova;

use Coroowicaksono\ChartJsIntegration\LineChart;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\HasMany;
use Rimu\FormattedNumber\FormattedNumber;

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
    public static $title = 'equipment_name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'equipment_number',
        'equipment_name',
        'manufacture',
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
            ID::make()->sortable(),

            BelongsTo::make('Category', 'category', BuildingEquipmentCategory::class)
                ->rules('required'),

            BelongsTo::make('Building', 'building', Building::class)
                ->rules('required'),

            Text::make('Equipment Number', 'equipment_number')
                ->rules('required', 'string'),

            Text::make('Equipment Name', 'equipment_name')
                ->rules('string')
                ->showOnIndex()
                ->showOnCreating()
                ->showOnUpdating(),

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
                ->rules('required', 'string')
                ->onlyOnDetail()
                ->onlyOnForms(),

            Text::make('Year of Construction')
                ->rules('required', 'numeric', 'min:1990', 'max:' . date('Y')),

            FormattedNumber::make('Cost Center')
                ->rules(['required', 'numeric']),

            Textarea::make('Location')
                ->rules('required')
                ->alwaysShow(),

            Text::make('Barcode Number')
                ->rules('required')
                ->onlyOnForms()
                ->showOnDetail(),

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
        return [
            $this->monthlyCostChart($request),
        ];
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
        return 'Item Equipment';
    }

    protected function monthlyCostChart(Request $request)
    {
        // Collect the last 12 months.
        $months = collect([]);
        // Collect the series data.
        $seriesData = collect([]);

        // Get the series data.
        for ($month = 11; $month >= 0; $month--) {
            $months->push(now()->subMonths($month)->format('M Y'));

            // Get the sum of every month, and push to the $seriesData collections
            $cost = \App\BuildingEquipmentHistory::query()
                ->where('building_equipment_id', $request->get('resourceId'))
                ->whereYear('date_of_problem_fixed', now()->subMonths($month)->format('Y'))
                ->whereMonth('date_of_problem_fixed', now()->subMonths($month)->format('m'))
                ->sum('cost');

            $seriesData->push($cost);
        }

        //return the line chart with the value
        return (new LineChart())
            ->title('Total biaya perawatan 12 bulan terakhir')
            ->animations([
                'enabled' => true,
                'easing'  => 'easeinout',
            ])
            ->series([[
                'barPercentage' => 1,
                'label'         => 'Biaya Perawatan',
                'borderColor'   => '#f7a35c',
                'data'          => $seriesData->toArray(),
            ]])
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
}
