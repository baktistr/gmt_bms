<?php

namespace App\Nova;

use App\Nova\Metrics\TotalBuildings;
use App\Statistics\Statistik;
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

            Text::make('Nama Gedung', 'name'),

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

            HasMany::make('Attendances', 'attendances', BuildingEmployeeAttendance::class),

            HasMany::make('Employees', 'employees', BuildingEmployee::class),

            HasMany::make('Meteran Gedung', 'buildingElectricityMeters', BuildingElectricityMeter::class),

            HasMany::make('Pemakaian Listrik Harian', 'electricityConsumptions', BuildingElectricityConsumption::class),

            HasMany::make('Pemakaian Air', 'waterConsumptions', BuildingWaterConsumption::class),

            HasMany::make('Diesel Fuel Consumptions', 'dieselFuelConsumptions', BuildingDieselFuelConsumption::class),

            HasMany::make('Equipments', 'equipments', BuildingEquipment::class),

            //            HasMany::make('Complaints', 'complaints', HelpDesk::class),

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

        $user = $request->user();

        if ($user->hasRole('Super Admin')) {
            // Super Admin
            return [

                (new TotalBuildings)->canSee(function () use ($request) {
                    return $request->user()->hasRole('Super Admin');
                }),

                (new Statistik($request->get('resourceId')))
                    ->monthlyWaterConsumptions()
                    ->onlyOnDetail(),

                (new Statistik($request->get('resourceId')))
                    ->monthlyElectricityChart()
                    ->onlyOnDetail(),

                (new Statistik($request->get('resourceId')))
                    ->monthlyDieselFuelChart()
                    ->onlyOnDetail(),
            ];
        }

        if (($user->hasRole('Help Desk') || $user->hasRole('Viewer')) || $user->hasRole('Building Manager') && $user->building_id) {
            return [
                (new Statistik($user->building_id))
                    ->monthlyWaterConsumptions(),

                (new Statistik($user->building_id))
                    ->monthlyElectricityChart(),

                (new Statistik($user->building_id))
                    ->monthlyDieselFuelChart(),
            ];
        } else {
            return [];
        }
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
