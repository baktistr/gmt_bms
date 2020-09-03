<?php

namespace App\Nova\Actions;

use App\BuildingEmployeeAttendance;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Select;

class UpdateAttendanceStatus extends Action
{
    /**
     * The displayable name of the action.
     *
     * @var string
     */
    public $name = 'Update Status';

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        foreach ($models as $model) {
            $model->update(['status' => $fields->status]);
        }
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [
            Select::make('Status')
                ->options(BuildingEmployeeAttendance::$statuses)
                ->displayUsingLabels()
                ->rules('required'),
        ];
    }
}
