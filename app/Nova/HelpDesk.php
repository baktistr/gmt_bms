<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Markdown;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class HelpDesk extends Resource
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
    public static $model = \App\HelpDesk::class;

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
        'status',
        'message',
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

        if ($user->hasRole('Help Desk')) {
            $query->where('help_desk_id', $user->id);
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
            Text::make('Priority' , 'priority')
                ->exceptOnForms(),
          
            Select::make('Priority')
                ->options(\App\HelpDesk::$priority)
                ->rules('required')
                ->sortable()
                ->displayUsingLabels(),

            BelongsTo::make('Category', 'category', HelpDeskCategory::class)
                ->rules('required')
                ->withoutTrashed(),

            BelongsTo::make('Building', 'building', Building::class)
                ->rules('required')
                ->withoutTrashed(),

            BelongsTo::make('Help Desk', 'helpDesk', User::class)
                ->withoutTrashed(),

            Text::make('Title', 'title')
                ->rules('required', 'string'),

            Markdown::make('Message', 'message')
                ->rules('required', 'string')
                ->onlyOnForms()->showOnDetail(),

            Select::make('Status')
                ->options(\App\HelpDesk::$statuses)
                ->displayUsingLabels(),
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
