<?php

namespace App\Policies;

use App\Enums\RoleEnum;
use App\Models\Property;
use App\Models\Unit;
use App\Models\User;

class UnitPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole([RoleEnum::ADMIN->value, RoleEnum::MANAGER->value]);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Unit $unit): bool
    {
        if ($user->hasRole(RoleEnum::ADMIN->value)) {
            return true;
        }

        if ($user->hasRole(RoleEnum::MANAGER->value)) {
            return $unit->property->manager_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Property $property): bool
    {
        if ($user->hasRole(RoleEnum::ADMIN->value)) {
            return true;
        }

        return $user->hasRole(RoleEnum::MANAGER->value)
            && $property->manager_id === $user->id;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Unit $unit): bool
    {
        if ($user->hasRole(RoleEnum::MANAGER->value)) {
            return true;
        }

        return $user->hasRole(RoleEnum::MANAGER->value)
            && $unit->property->manager_id === $user->id;
    }


    /**
     * Determine whether the user can assign a tenant.
     */
    public function assignTenant(User $user, Unit $unit)
    {
        return $user->hasRole(RoleEnum::MANAGER->value);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Unit $unit): bool
    {
        if ($user->hasRole(RoleEnum::ADMIN->value)) {
            return true;
        }

        return $user->hasRole(RoleEnum::MANAGER->value)
            && $unit->property->manager_id === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Unit $unit): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Unit $unit): bool
    {
        return false;
    }
}
