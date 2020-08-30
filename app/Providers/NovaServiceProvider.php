<?php

namespace App\Providers;

use App\Nova\Metrics\TotalBuildings;
use App\Nova\Metrics\TotalElectricityConsumption;
use App\Nova\Metrics\TotalEmployees;
use App\Nova\Metrics\TotalHelpDesks;
use App\Nova\Metrics\TotalManagers;
use App\Nova\Metrics\TotalRemainFuel;
use App\Nova\Metrics\TotalViewers;
use App\Nova\Metrics\TotalWaterConsumption;
use App\Policies\PermissionPolicy;
use App\Policies\RolePolicy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Laravel\Nova\Cards\Help;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;
use Vyuldashev\NovaPermission\NovaPermissionTool;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        Nova::routes()
            ->withAuthenticationRoutes()
            ->withPasswordResetRoutes()
            ->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewNova', function ($user) {
            return in_array($user->email, [
                //
            ]);
        });
    }

    /**
     * Get the cards that should be displayed on the default Nova dashboard.
     *
     * @return array
     */
    protected function cards()
    {
        return [
            (new TotalBuildings)->canSee(function () {
                return Auth::user()->hasRole('Super Admin');
            })->width('1/4'),
            (new TotalManagers)->canSee(function () {
                return Auth::user()->hasRole('Super Admin');
            })->width('1/4'),
            (new TotalHelpDesks)->canSee(function () {
                return Auth::user()->hasRole('Super Admin');
            })->width('1/4'),
            (new TotalViewers)->canSee(function () {
                return Auth::user()->hasRole('Super Admin');
            })->width('1/4'),               

            (new TotalEmployees)->canSee(function () {
                return Auth::user()->hasRole('Building Manager');
            })->width('1/4'),
            (new TotalElectricityConsumption)->canSee(function () {
                return Auth::user()->hasRole('Building Manager');
            })->width('1/4'),
            (new TotalWaterConsumption)->canSee(function () {
                return Auth::user()->hasRole('Building Manager');
            })->width('1/4'),
            (new TotalRemainFuel)->canSee(function () {
                return Auth::user()->hasRole('Building Manager');
            })->width('1/4'),
        ];
    }

    /**
     * Get the extra dashboards that should be displayed on the Nova dashboard.
     *
     * @return array
     */
    protected function dashboards()
    {
        return [];
    }

    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
        return [
            NovaPermissionTool::make()
                ->rolePolicy(RolePolicy::class)
                ->permissionPolicy(PermissionPolicy::class),
        ];
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
