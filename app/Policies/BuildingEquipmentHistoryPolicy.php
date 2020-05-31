<?php

namespace App\Policies;

use App\BuildingEquipmentHistory;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BuildingEquipmentHistoryPolicy
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
        return $user->hasPermissionTo('View All Buildings');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\BuildingEquipmentHistory  $buildingEquipmentHistory
     * @return mixed
     */
    public function view(User $user, BuildingEquipmentHistory $buildingEquipmentHistory)
    {
        return $user->hasPermissionTo('View Building');
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
     * @param  \App\User  $user
     * @param  \App\BuildingEquipmentHistory  $buildingEquipmentHistory
     * @return mixed
     */
    public function update(User $user, BuildingEquipmentHistory $buildingEquipmentHistory)
    {
        return $user->hasPermissionTo('Update Building');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\BuildingEquipmentHistory  $buildingEquipmentHistory
     * @return mixed
     */
    public function delete(User $user, BuildingEquipmentHistory $buildingEquipmentHistory)
    {
        return $user->hasPermissionTo('View Building');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\BuildingEquipmentHistory  $buildingEquipmentHistory
     * @return mixed
     */
    public function restore(User $user, BuildingEquipmentHistory $buildingEquipmentHistory)
    {
        return $user->hasPermissionTo('View Building');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\BuildingEquipmentHistory  $buildingEquipmentHistory
     * @return mixed
     */
    public function forceDelete(User $user, BuildingEquipmentHistory $buildingEquipmentHistory)
    {
        return $user->hasPermissionTo('View Building');
    }
}
