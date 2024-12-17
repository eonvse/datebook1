<?php

namespace App\Policies;

use App\Models\MaterialCategory;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MaterialCategoryPolicy
{

    public function before(User $user, string $ability): bool|null
    {
        if ($user->hasRole('Root')) {
            return true;
        }

        return null;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('Materials Admin|Materials Manager');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, MaterialCategory $materialCategory): bool
    {
        return $user->hasRole('Materials Admin');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('Materials Admin');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, MaterialCategory $materialCategory): bool
    {
        return $user->hasRole('Materials Admin');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MaterialCategory $materialCategory): bool
    {
        return $user->hasRole('Materials Admin');
    }

    public function deleteAny(User $user): bool
    {
        return $user->hasRole('Materials Admin');
    }

}
