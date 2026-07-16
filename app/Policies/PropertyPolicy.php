<?php

namespace App\Policies;

use App\Enums\RoleEnum;
use App\Models\Property;
use App\Models\User;

class PropertyPolicy
{

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        if ($user->hasRole(RoleEnum::ADMIN->value) || $user->hasRole(RoleEnum::MANAGER->value)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Property $property): bool
    {
        if ($user->hasRole(RoleEnum::ADMIN->value)) {
            return true;
        }

        if ($user->hasRole(RoleEnum::MANAGER->value)) {
            return $property->manager_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole(RoleEnum::ADMIN->value);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Property $property): bool
    {
        if ($user->hasRole(RoleEnum::ADMIN->value)) {
            return true;
        }

        return $user->hasRole(RoleEnum::MANAGER->value) && $property->manager_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Property $property): bool
    {
        if ($user->hasRole(RoleEnum::ADMIN->value)) {
            return true;
        }

        return $user->hasRole(RoleEnum::MANAGER->value) && $property->manager_id === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Property $property): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Property $property): bool
    {
        return false;
    }
}
