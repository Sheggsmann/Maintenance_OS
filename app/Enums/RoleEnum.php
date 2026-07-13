<?php

namespace App\Enums;

enum RoleEnum: string
{
    case ADMIN = 'Admin';
    case MANAGER = 'Manager';
    case TENANT = 'Tenant';
    case CONTRACTOR = 'Contractor';
}

enum PublicRoleEnums: string
{
    case MANAGER = 'Manager';
    case TENANT = 'Tenant';
    case CONTRACTOR = 'Contractor';
}
