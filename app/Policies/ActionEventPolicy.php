<?php

namespace App\Policies;

use Laravel\Nova\Actions\ActionEvent;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ActionEventPolicy
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
        $user->hasRole('Super Admin');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param \App\User                         $user
     * @param \Laravel\Nova\Actions\ActionEvent $actionEvent
     * @return mixed
     */
    public function view(User $user, ActionEvent $actionEvent)
    {
        $user->hasRole('Super Admin');
    }
}
