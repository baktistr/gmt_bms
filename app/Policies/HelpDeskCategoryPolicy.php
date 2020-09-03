<?php

namespace App\Policies;

use App\BuildingHelpDeskCategory;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class HelpDeskCategoryPolicy
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
     * @param  \App\BuildingHelpDeskCategory $helpDeskCategory
     * @return mixed
     */
    public function view(User $user, BuildingHelpDeskCategory $helpDeskCategory)
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
     * @param  \App\User                     $user
     * @param  \App\BuildingHelpDeskCategory $helpDeskCategory
     * @return mixed
     */
    public function update(User $user, BuildingHelpDeskCategory $helpDeskCategory)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User                     $user
     * @param  \App\BuildingHelpDeskCategory $helpDeskCategory
     * @return mixed
     */
    public function delete(User $user, BuildingHelpDeskCategory $helpDeskCategory)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User                     $user
     * @param  \App\BuildingHelpDeskCategory $helpDeskCategory
     * @return mixed
     */
    public function restore(User $user, BuildingHelpDeskCategory $helpDeskCategory)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User                     $user
     * @param  \App\BuildingHelpDeskCategory $helpDeskCategory
     * @return mixed
     */
    public function forceDelete(User $user, BuildingHelpDeskCategory $helpDeskCategory)
    {
        return false;
    }
}
