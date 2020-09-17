<?php

namespace App\Nova;

use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Epartment\NovaDependencyContainer\NovaDependencyContainer;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Rimu\FormattedNumber\FormattedNumber;

class Procurement extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $group = 'Help Desk';


    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Procurement::class;

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
        'title'
    ];

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

            BelongsTo::make('Equipment', 'equipment', BuildingEquipment::class)
                ->onlyOnIndex(),

            Select::make('Category', 'help_desk_category_id')
                ->options(\App\BuildingEquipmentCategory::all()->pluck('name', 'id'))
                ->displayUsingLabels()
                ->onlyOnForms(),

            // Mechanical 
            NovaDependencyContainer::make([
                Select::make('Equipment', 'building_equipment_id')
                    ->options(\App\BuildingEquipment::query()->where('building_equipment_category_id', '1')
                        ->where('building_id', $request->user()->building->id ?? 0)
                        ->pluck('equipment_name', 'id'))
                    ->displayUsingLabels()
                    ->onlyOnForms(),
            ])->dependsOn('help_desk_category_id', '1')
                ->onlyOnForms(),

            // Electrical
            NovaDependencyContainer::make([
                Select::make('Equipment', 'building_equipment_id')
                    ->options(\App\BuildingEquipment::query()->where('building_equipment_category_id', '2')
                        ->where('building_id', $request->user()->building->id ?? 0)
                        ->pluck('equipment_name', 'id'))
                    ->displayUsingLabels()
                    ->onlyOnForms(),
            ])->dependsOn('help_desk_category_id', '2')
                ->onlyOnForms(),

            // Furniture
            NovaDependencyContainer::make([
                Select::make('Equipment', 'building_equipment_id')
                    ->options(\App\BuildingEquipment::query()->where('building_equipment_category_id', '3')
                        ->where('building_id', $request->user()->building->id ?? 0)
                        ->pluck('equipment_name', 'id'))
                    ->displayUsingLabels()
                    ->onlyOnForms(),
            ])->dependsOn('help_desk_category_id', '3')
                ->onlyOnForms(),

            // Civil
            NovaDependencyContainer::make([
                Select::make('Equipment', 'building_equipment_id')
                    ->options(\App\BuildingEquipment::query()->where('building_equipment_category_id', '4')
                        ->where('building_id', $request->user()->building->id ?? 0)
                        ->pluck('equipment_name', 'id'))
                    ->displayUsingLabels()
                    ->onlyOnForms(),
            ])->dependsOn('help_desk_category_id', '4')
                ->onlyOnForms(),

            // other
            NovaDependencyContainer::make([
                Select::make('Equipment', 'building_equipment_id')
                    ->options(\App\BuildingEquipment::query()->where('building_equipment_category_id', '5')
                        ->where('building_id', $request->user()->building->id ?? 0)
                        ->pluck('equipment_name', 'id'))
                    ->displayUsingLabels()
                    ->onlyOnForms(),
            ])->dependsOn('help_desk_category_id', '5')
                ->onlyOnForms(),

            Text::make('Title', 'title')
                ->rules('required', 'string'),

            Date::make('Date Of Problem', 'date_of_problem')
                ->rules(['required', 'date_format:Y-m-d'])
                ->withMeta([
                    'value' => now()->format('Y-m-d'),
                ])
                ->onlyOnForms(),

            Date::make('Date Of Problem', 'date_of_problem', function () {
                return $this->formatted_problem;
            })->exceptOnForms(),

            Date::make('Date Of Problem Fixed', 'date_of_problem_fixed')
                ->rules(['required', 'date_format:Y-m-d'])
                ->withMeta([
                    'value' => now()->format('Y-m-d'),
                ])
                ->onlyOnForms(),

            Date::make('Date of Problem Fixed', 'date_of_problem_fixed', function () {
                return $this->formatted_fixed;
            })->exceptOnForms(),

            Select::make('Action')
                ->options(\App\BuildingEquipmentHistory::$type)
                ->displayUsingLabels(),

            FormattedNumber::make('Cost', 'cost')
                ->rules(['required', 'numeric'])
                ->onlyOnForms(),

            Text::make('Cost', function () {
                return $this->formatted_cost;
            })->exceptOnForms(),

            Textarea::make('Keterangan', 'message')
                ->alwaysShow(),

            Textarea::make('Additional Information')
                ->alwaysShow(),

            Images::make('Procurement', 'procurement')
                ->nullable()

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
        return [];
    }
}
