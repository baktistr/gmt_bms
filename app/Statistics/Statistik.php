<?php

namespace App\Statistics;

use Coroowicaksono\ChartJsIntegration\LineChart;
use Illuminate\Http\Request;

class Statistik
{
    protected $building_id;

    public function __construct($building_id)
    {
        $this->building_id = $building_id;
    }

    /**
     * Add Monthly Chart
     *
     * @return LineChart
     */
    public function monthlyDieselFuelChart()
    {
        // Collect the last 12 months.
        $months = collect([]);
        // Collect the series data.
        $seriesData = collect([]);

        $seriesData2 = collect([]);

        for ($month = 11; $month >= 0; $month--) {
            $months->push(now()->subMonths($month)->format('M Y'));
            $income = \App\BuildingDieselFuelConsumption::query()
                ->where('building_id', $this->building_id)
                ->where('type', 'incoming')
                ->whereYear('date', now()->subMonths($month)->format('Y'))
                ->whereMonth('date', now()->subMonths($month)->format('m'))
                ->sum('amount');

            $remain = \App\BuildingDieselFuelConsumption::query()
                ->where('type', 'remain')
                ->where('building_id', $this->building_id)
                ->whereYear('date', now()->subMonths($month)->format('Y'))
                ->whereMonth('date', now()->subMonths($month)->format('m'))
                ->sum('amount');

            $seriesData->push($income);

            $seriesData2->push($remain);
        }

        return (new LineChart())
            ->title('Pengunaan solar 12 bulan terakhir')
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
                            var value = ': ' + data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index].toLocaleString() + ' liter';	
                            	
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
            ->width('full');
    }

    /**
     * Chart Listrik
     *
     * @return LineChart
     */
    public function monthlyElectricityChart()
    {
        // Collect the last 12 months.
        $months = collect([]);
        // Collect the series data.
        $seriesData = collect([]);
        for ($month = 11; $month >= 0; $month--) {
            $months->push(now()->subMonths($month)->format('M Y'));
            $listrik = \App\BuildingElectricityConsumption::query()
                ->where('building_id', $this->building_id)
                ->whereMonth('date', now()->subMonths($month)->format('m'))
                ->get();
            $totalCost = $listrik->map(function ($cost) {
                return $cost->totalCost();
            });
            $seriesData->push($totalCost);
        }

        $totalCost = collect([]);

        for ($i = 0; $i < count($seriesData); $i++) {
            $totalCost->push($seriesData[$i]->sum());
        }

        return (new LineChart())
            ->title('Penggunaan listrik 12 bulan terakhir')
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
            ->width('full');
    }

    /**
     * Water Consumption Chart
     * 
     * @return LineChart
     */
    public function monthlyWaterConsumptions()
    {
        $months = collect([]);
        $waterSeries = collect([]);

        for ($month = 11; $month >= 0; $month--) {
            $months->add(now()->subMonths($month)->format('M Y'));

            $waterConsumption = \App\BuildingWaterConsumption::query()
                ->where('building_id', $this->building_id)
                ->whereMonth('date', now()->subMonths($month)->format('m'))
                ->get();

            $waterSeries->push($waterConsumption->sum('usage'));
        }

        return (new LineChart())
            ->title('Penggunaan air 12 bulan terakhir')
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
            ->width('full');
    }
}
