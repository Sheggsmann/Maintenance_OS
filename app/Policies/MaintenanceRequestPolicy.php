<?php

namespace App\Policies;

use App\Enums\RoleEnum;
use App\Models\MaintenanceRequest;
use App\Models\User;

class MaintenanceRequestPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole([
            RoleEnum::ADMIN->value,
            RoleEnum::MANAGER->value,
            RoleEnum::TENANT->value,
        ]);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, MaintenanceRequest $request): bool
    {
        if ($user->hasRole(RoleEnum::ADMIN->value)) {
            return true;
        }

        if ($user->hasRole(RoleEnum::MANAGER->value)) {
            return $request->property->manager_id === $user->id;
        }

        if ($user->hasRole(RoleEnum::TENANT->value)) {
            return $request->tenant_id === $user->id;
        }

        if ($user->hasRole(RoleEnum::CONTRACTOR->value)) {
            return $request->assigned_to === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole([
            RoleEnum::ADMIN->value,
            RoleEnum::MANAGER->value,
            RoleEnum::TENANT->value,
        ]);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, MaintenanceRequest $maintenanceRequest): bool
    {
        if ($user->hasRole(RoleEnum::ADMIN->value)) {
            return true;
        }

        if ($user->hasRole(RoleEnum::MANAGER->value)) {
            return $maintenanceRequest->property->manager_id === $user->id;
        }

        return false;
    }


    /**
     * Determine whether the user can assign a contractor the model.
     */
    public function assign(User $user, MaintenanceRequest $maintenanceRequest): bool
    {
        if ($user->hasRole(RoleEnum::ADMIN->value)) {
            return true;
        }

        if ($user->hasRole(RoleEnum::MANAGER->value)) {
            return $maintenanceRequest->property->manager_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can start a maintenance request.
     */
    public function start(User $user, MaintenanceRequest $maintenanceRequest): bool
    {
        return $user->hasRole(RoleEnum::CONTRACTOR->value)
            && $maintenanceRequest->assigned_to === $user->id;
    }


    /**
     * Determine whether the user can complete a maintenance request.
     */
    public function complete(
        User $user,
        MaintenanceRequest $maintenanceRequest,
    ): bool {

        return $user->hasRole(RoleEnum::CONTRACTOR->value)
            && $maintenanceRequest->assigned_to === $user->id;
    }


    /**
     * Determine whether the user can cancel a maintenance request.
     */
    public function cancel(
        User $user,
        MaintenanceRequest $maintenanceRequest,
    ): bool {

        if ($user->hasRole(RoleEnum::ADMIN->value)) {
            return true;
        }

        if ($user->hasRole(RoleEnum::MANAGER->value)) {
            return $maintenanceRequest->property->manager_id === $user->id;
        }

        if ($user->hasRole(RoleEnum::TENANT->value)) {
            return $maintenanceRequest->tenant_id === $user->id;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MaintenanceRequest $maintenanceRequest): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, MaintenanceRequest $maintenanceRequest): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, MaintenanceRequest $maintenanceRequest): bool
    {
        return false;
    }
}
