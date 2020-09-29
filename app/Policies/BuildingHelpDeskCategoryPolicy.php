<?php

namespace App\Policies;

use App\BuildingHelpdeskCategory;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BuildingHelpDeskCategoryPolicy
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
     * @param  \App\BuildingHelpdeskCategory  $buildingHelpdeskCategory
     * @return mixed
     */
    public function view(User $user, BuildingHelpdeskCategory $buildingHelpdeskCategory)
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
        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\BuildingHelpdeskCategory  $buildingHelpdeskCategory
     * @return mixed
     */
    public function update(User $user, BuildingHelpdeskCategory $buildingHelpdeskCategory)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\BuildingHelpdeskCategory  $buildingHelpdeskCategory
     * @return mixed
     */
    public function delete(User $user, BuildingHelpdeskCategory $buildingHelpdeskCategory)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\BuildingHelpdeskCategory  $buildingHelpdeskCategory
     * @return mixed
     */
    public function restore(User $user, BuildingHelpdeskCategory $buildingHelpdeskCategory)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\BuildingHelpdeskCategory  $buildingHelpdeskCategory
     * @return mixed
     */
    public function forceDelete(User $user, BuildingHelpdeskCategory $buildingHelpdeskCategory)
    {
        return false;
    }
}
