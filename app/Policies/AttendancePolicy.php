<?php

namespace App\Policies;

use App\BuildingEmployeeAttendance;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AttendancePolicy
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
     * @param  \App\User                       $user
     * @param  \App\BuildingEmployeeAttendance $attendance
     * @return mixed
     */
    public function view(User $user, BuildingEmployeeAttendance $attendance)
    {
        return $user->hasRole('Building Manager');
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
     * @param  \App\User                       $user
     * @param  \App\BuildingEmployeeAttendance $attendance
     * @return mixed
     */
    public function update(User $user, BuildingEmployeeAttendance $attendance)
    {
        return $user->hasRole('Building Manager');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User                       $user
     * @param  \App\BuildingEmployeeAttendance $attendance
     * @return mixed
     */
    public function delete(User $user, BuildingEmployeeAttendance $attendance)
    {
        return $user->hasRole('Building Manager');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User                       $user
     * @param  \App\BuildingEmployeeAttendance $attendance
     * @return mixed
     */
    public function restore(User $user, BuildingEmployeeAttendance $attendance)
    {
        return $user->hasRole('Building Manager');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User                       $user
     * @param  \App\BuildingEmployeeAttendance $attendance
     * @return mixed
     */
    public function forceDelete(User $user, BuildingEmployeeAttendance $attendance)
    {
        return $user->hasRole('Building Manager');
    }
}
