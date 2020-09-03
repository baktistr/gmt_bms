<?php

namespace App\Providers;

use App\BuildingEmployeeAttendance;
use App\Building;
use App\BuildingEquipment;
use App\BuildingEquipmentCategory;
use App\District;
use App\BuildingElectricityConsumption;
use App\BuildingEmployee;
use App\Policies\ActionEventPolicy;
use App\Policies\AttendancePolicy;
use App\Policies\BuildingEquipmentCategoryPolicy;
use App\Policies\BuildingEquipmentPolicy;
use App\Policies\BuildingPolicy;
use App\Policies\DistrictPolicy;
use App\Policies\BuildingElectricityConsumptionPolicy;
use App\Policies\BuildingEmployeePolicy;
use App\Policies\ProvincePolicy;
use App\Policies\RegencyPolicy;
use App\Policies\UserPolicy;
use App\Policies\BuildingWaterConsumptionPolicy;
use App\Province;
use App\Regency;
use App\User;
use App\BuildingWaterConsumption;
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
        ActionEvent::class                    => ActionEventPolicy::class,
        User::class                           => UserPolicy::class,
        Province::class                       => ProvincePolicy::class,
        Regency::class                        => RegencyPolicy::class,
        District::class                       => DistrictPolicy::class,
        Building::class                       => BuildingPolicy::class,
        BuildingElectricityConsumption::class => BuildingElectricityConsumptionPolicy::class,
        BuildingWaterConsumption::class       => BuildingWaterConsumptionPolicy::class,
        BuildingEquipmentCategory::class      => BuildingEquipmentCategoryPolicy::class,
        BuildingEquipment::class              => BuildingEquipmentPolicy::class,
        BuildingEmployee::class               => BuildingEmployeePolicy::class,
        BuildingEmployeeAttendance::class     => AttendancePolicy::class,
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
