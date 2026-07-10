<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Utils\Permissions;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            Permissions::CREATE_MAINTENANCE_REQUEST,
            Permissions::VIEW_MAINTENANCE_REQUESTS,
            Permissions::APPROVE_MAINTENANCE_REQUESTS,

            Permissions::CREATE_WORK_ORDER,
            Permissions::ASSIGN_WORK_ORDER,
            Permissions::UPDATE_WORK_ORDER,

            Permissions::MANAGE_PROPERTIES,
            Permissions::MANAGE_UNITS,

            Permissions::MANAGE_USERS,
            Permissions::VIEW_REPORTS,
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'sanctum']);
        }


        $admin = Role::firstOrCreate(['name' => RoleEnum::ADMIN->value, 'guard_name' => 'sanctum']);

        $manager = Role::firstOrCreate(['name' => RoleEnum::MANAGER->value, 'guard_name' => 'sanctum']);

        $tenant = Role::firstOrCreate(['name' => RoleEnum::TENANT->value, 'guard_name' => 'sanctum']);

        $contractor = Role::firstOrCreate(['name' => RoleEnum::CONTRACTOR->value, 'guard_name' => 'sanctum']);

        $admin->givePermissionTo(Permission::all());

        $manager->givePermissionTo([
            Permissions::VIEW_MAINTENANCE_REQUESTS,
            Permissions::APPROVE_MAINTENANCE_REQUESTS,
            Permissions::CREATE_WORK_ORDER,
            Permissions::ASSIGN_WORK_ORDER,
            Permissions::MANAGE_PROPERTIES,
            Permissions::MANAGE_UNITS,
            Permissions::VIEW_REPORTS
        ]);

        $tenant->givePermissionTo([
            Permissions::CREATE_MAINTENANCE_REQUEST,
            Permissions::VIEW_MAINTENANCE_REQUESTS,
        ]);

        $contractor->givePermissionTo([
            Permissions::UPDATE_WORK_ORDER,
        ]);
    }
}
