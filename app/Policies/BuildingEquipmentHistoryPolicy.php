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
        return $user->hasRole(['Building Manager', 'Help Desk', 'Viewer']);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\BuildingEquipmentHistory  $history
     * @return mixed
     */
    public function view(User $user, BuildingEquipmentHistory $history)
    {
        return $user->hasRole(['Building Manager', 'Help Desk', 'Viewer']);
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
     * @param  \App\BuildingEquipmentHistory  $history
     * @return mixed
     */
    public function update(User $user, BuildingEquipmentHistory $history)
    {
        return $user->hasRole('Building Manager')
            && ($user->id == $history->equipment->building->manager_id || $user->building_id == $history->equipment->building->id);    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\BuildingEquipmentHistory  $history
     * @return mixed
     */
    public function delete(User $user, BuildingEquipmentHistory $history)
    {
        return $user->hasRole('Building Manager')
            && ($user->id == $history->equipment->building->manager_id || $user->building_id == $history->equipment->building->id);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\BuildingEquipmentHistory  $history
     * @return mixed
     */
    public function restore(User $user, BuildingEquipmentHistory $history)
    {
        return $user->hasRole('Building Manager')
            && ($user->id == $history->equipment->building->manager_id || $user->building_id == $history->equipment->building->id);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\BuildingEquipmentHistory  $history
     * @return mixed
     */
    public function forceDelete(User $user, BuildingEquipmentHistory $history)
    {
        return $user->hasRole('Building Manager')
            && ($user->id == $history->equipment->building->manager_id || $user->building_id == $history->equipment->building->id);
    }
}
