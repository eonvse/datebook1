<?php

namespace App\Policies;

use App\Models\LogActivity;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class LogActivityPolicy
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
        return $user->hasRole('Admin');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, LogActivity $logActivity): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, LogActivity $logActivity): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, LogActivity $logActivity): bool
    {
        return false;
    }

    public function deleteAny(User $user): bool
    {
        return false;
    }

}
