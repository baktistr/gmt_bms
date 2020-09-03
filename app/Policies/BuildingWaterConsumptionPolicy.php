<?php

namespace App\Policies;

use App\BuildingWaterConsumption;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BuildingWaterConsumptionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasRole(['Building Manager', 'Help Desk', 'Viewer']);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User                     $user
     * @param  \App\BuildingWaterConsumption $consumption
     * @return mixed
     */
    public function view(User $user, BuildingWaterConsumption $consumption)
    {
        return $user->hasRole(['Building Manager', 'Help Desk', 'Viewer'])
            && ($user->id == $consumption->building->manager_id || $user->building_id == $consumption->building_id);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasRole('Building Manager');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User                     $user
     * @param  \App\BuildingWaterConsumption $consumption
     * @return mixed
     */
    public function update(User $user, BuildingWaterConsumption $consumption)
    {
        return $user->hasRole('Building Manager')
            && $user->id == $consumption->building->manager_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User                     $user
     * @param  \App\BuildingWaterConsumption $consumption
     * @return mixed
     */
    public function delete(User $user, BuildingWaterConsumption $consumption)
    {
        return $user->hasRole('Building Manager')
            && $user->id == $consumption->building->manager_id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User                     $user
     * @param  \App\BuildingWaterConsumption $consumption
     * @return mixed
     */
    public function restore(User $user, BuildingWaterConsumption $consumption)
    {
        return $user->hasRole('Building Manager')
            && $user->id == $consumption->building->manager_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User                     $user
     * @param  \App\BuildingWaterConsumption $consumption
     * @return mixed
     */
    public function forceDelete(User $user, BuildingWaterConsumption $consumption)
    {
        return $user->hasRole('Building Manager')
            && $user->id == $consumption->building->manager_id;
    }
}
