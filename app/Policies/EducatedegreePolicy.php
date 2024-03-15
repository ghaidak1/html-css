<?php

namespace App\Policies;

use App\Models\Educatedegree;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EducatedegreePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Educatedegree $educatedegree): bool
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
    public function update(User $user, Educatedegree $educatedegree): bool
    {
        return $user->id === $educatedegree->user_id;

    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Educatedegree $educatedegree): bool
    {
        return $user->id === $educatedegree->user_id;

    }

    
}
