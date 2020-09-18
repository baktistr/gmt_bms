<?php

namespace App\Nova;

use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Epartment\NovaDependencyContainer\NovaDependencyContainer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Rimu\FormattedNumber\FormattedNumber;

class BuildingProcurement extends Resource
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
    public static $model = \App\BuildingProcurement::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'title';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'title',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function fields(Request $request)
    {
        $equipment = null;

        if ($resourceId = $request->get('viaResourceId')) {
            $equipment = \App\BuildingEquipment::with('category')->find($resourceId);
        }

        return [
            Select::make('Category', 'help_desk_category_id')
                ->nullable()
                ->options(\App\BuildingEquipmentCategory::all()->pluck('name', 'id'))
                ->displayUsingLabels()
                ->onlyOnForms()
                ->withMeta([
                    'value' => $equipment ? $equipment->category->id : null
                ])
                ->readonly(function () use ($equipment) {
                    return $equipment && $equipment->exists();
                }),

            BelongsTo::make('Equipment', 'equipment', BuildingEquipment::class)
                ->exceptOnForms(),

            $this->selectItemsByCategory($request, 1, $equipment),

            $this->selectItemsByCategory($request, 2, $equipment),

            $this->selectItemsByCategory($request, 3, $equipment),

            $this->selectItemsByCategory($request, 4, $equipment),

            $this->selectItemsByCategory($request, 5, $equipment),


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
                ->options(\App\BuildingEquipmentHistory::$actions)
                ->rules(['required'])
                ->displayUsingLabels(),

            FormattedNumber::make('Cost', 'cost')
                ->rules(['required', 'numeric'])
                ->onlyOnForms(),

            Text::make('Cost', function () {
                return $this->formatted_cost;
            })->exceptOnForms(),

            Textarea::make('Keterangan', 'description')
                ->rules(['required'])
                ->alwaysShow(),

            Images::make('Photos', 'photos')
                ->nullable()
                ->hideFromIndex(),
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

    /**
     * Show select items by category.
     *
     * @param \Illuminate\Http\Request $request
     * @param                          $categoryId
     * @param                          $equipment
     * @return \Epartment\NovaDependencyContainer\NovaDependencyContainer
     */
    protected function selectItemsByCategory(Request $request, $categoryId, $equipment)
    {
        return NovaDependencyContainer::make([
            Select::make('Equipment', 'building_equipment_id')
                ->options(\App\BuildingEquipment::query()->where('building_equipment_category_id', $categoryId)
                    ->where('building_id', $request->user()->building->id ?? 0)
                    ->pluck('equipment_name', 'id'))
                ->displayUsingLabels()
                ->rules(['required'])
                ->onlyOnForms()
                ->withMeta([
                    'value' => $equipment ? $equipment->id : null
                ])
                ->readonly(function () use ($equipment) {
                    return $equipment && $equipment->exists();
                }),
        ])->dependsOn('help_desk_category_id', $categoryId)
            ->onlyOnForms();
    }

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return 'Procurements';
    }
}
