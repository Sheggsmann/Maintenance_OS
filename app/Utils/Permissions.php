<?php

namespace App\Utils;

class Permissions
{
    public const CREATE_MAINTENANCE_REQUEST = 'create maintenance request';
    public const VIEW_MAINTENANCE_REQUESTS = 'view maintenance requests';
    public const APPROVE_MAINTENANCE_REQUESTS = 'approve maintenance requests';

    public const CREATE_WORK_ORDER =      'create work order';
    public const ASSIGN_WORK_ORDER =       'assign work order';
    public const UPDATE_WORK_ORDER = 'update work order';

    public const MANAGE_PROPERTIES = 'manage properties';
    public const MANAGE_UNITS = 'manage units';

    public const MANAGE_USERS = 'manage users';
    public const VIEW_REPORTS = 'view reports';
}
