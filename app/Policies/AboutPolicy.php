<?php

namespace App\Policies;

use App\Models\About;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Auth\User as AuthUser;

class AboutPolicy
{


    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        return true;
    }



    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, About $about)
    {
        return $user->id === $about->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, About $about): bool
    {
        return $user->id === $about->user_id;

    }


}
