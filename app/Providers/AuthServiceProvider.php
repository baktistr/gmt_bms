<?php

namespace App\Providers;

use App\Building;
use App\District;
use App\ElectricityConsumption;
use App\Policies\ActionEventPolicy;
use App\Policies\BuildingPolicy;
use App\Policies\DistrictPolicy;
use App\Policies\ElectricityConsumptionPolicy;
use App\Policies\ProvincePolicy;
use App\Policies\RegencyPolicy;
use App\Policies\UserPolicy;
use App\Policies\WaterConsumptionPolicy;
use App\Province;
use App\Regency;
use App\User;
use App\WaterConsumption;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Nova\Actions\ActionEvent;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        ActionEvent::class            => ActionEventPolicy::class,
        User::class                   => UserPolicy::class,
        Building::class               => BuildingPolicy::class,
        ElectricityConsumption::class => ElectricityConsumptionPolicy::class,
        WaterConsumption::class       => WaterConsumptionPolicy::class,
        Province::class               => ProvincePolicy::class,
        Regency::class                => RegencyPolicy::class,
        District::class               => DistrictPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Implicitly grant "Super Admin" role all permissions
        // This works in the app by using gate-related functions like auth()->user->can() and @can()
        Gate::before(function ($user, $ability) {
            return $user->hasRole('Super Admin') ? true : null;
        });
    }
}
