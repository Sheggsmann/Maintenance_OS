<?php

namespace App\Policies;

use App\Enums\RoleEnum;
use App\Models\User;

class WorkOrderPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct() {}

    public function viewAny(
        User $user,
    ): bool {

        return $user->hasAnyRole([
            RoleEnum::ADMIN->value,
            RoleEnum::MANAGER->value
        ]);
    }
}
