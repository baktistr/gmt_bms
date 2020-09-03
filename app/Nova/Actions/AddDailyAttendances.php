<?php

namespace App\Nova\Actions;

use App\BuildingEmployeeAttendance;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Textarea;

class AddDailyAttendances extends Action
{
    /**
     * The displayable name of the action.
     *
     * @var string
     */
    public $name = 'Add Daily Attendance';

    /**
     * Perform the action on the given models.
     *
     * @param \Laravel\Nova\Fields\ActionFields $fields
     * @param \Illuminate\Support\Collection    $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        foreach ($models as $model) {
            $model->attendances()->updateOrCreate(
                [
                    'building_id' => $model->building_id,
                    'date'        => $fields->date,
                ],
                [
                    'date'   => $fields->date,
                    'status' => $fields->attendance,
                    'desc'   => $fields->desc,
                ]
            );
        }

        return Action::message('The daily attendances has been added.');
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [
            Date::make('Date')
                ->withMeta([
                    'value' => $this->date ?? now()->format('Y-m-d'),
                ])
                ->rules(['required', 'date_format:Y-m-d'])
                ->format('DD MMMM YYYY')
                ->sortable(),

            Select::make('Attendance')
                ->options(BuildingEmployeeAttendance::$statuses)
                ->displayUsingLabels()
                ->rules('required')
                ->sortable(),

            Textarea::make('Description', 'desc')
                ->rules(['nullable', 'required_unless:attendance,present']),
        ];
    }
}
