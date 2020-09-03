<?php

namespace App\Policies;

use App\BuildingEmployee;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BuildingEmployeePolicy
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
        return $user->hasRole('Building Manager');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User             $user
     * @param  \App\BuildingEmployee $employee
     * @return mixed
     */
    public function view(User $user, BuildingEmployee $employee)
    {
        return $user->hasRole('Building Manager') && $employee->building->manager_id === $user->id;
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
     * @param  \App\User             $user
     * @param  \App\BuildingEmployee $employee
     * @return mixed
     */
    public function update(User $user, BuildingEmployee $employee)
    {
        return $user->hasRole('Building Manager') && $employee->building->manager_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User             $user
     * @param  \App\BuildingEmployee $employee
     * @return mixed
     */
    public function delete(User $user, BuildingEmployee $employee)
    {
        return $user->hasRole('Building Manager') && $employee->building->manager_id === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User             $user
     * @param  \App\BuildingEmployee $employee
     * @return mixed
     */
    public function restore(User $user, BuildingEmployee $employee)
    {
        return $user->hasRole('Building Manager') && $employee->building->manager_id === $user->id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User             $user
     * @param  \App\BuildingEmployee $employee
     * @return mixed
     */
    public function forceDelete(User $user, BuildingEmployee $employee)
    {
        return $user->hasRole('Building Manager') && $employee->building->manager_id === $user->id;
    }
}
