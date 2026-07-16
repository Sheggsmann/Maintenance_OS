<?php

namespace App\Enums;

enum UnitStatusEnum: string
{
    case VACANT = 'vacant';
    case OCCUPIED = 'occupied';
    case MAINTENANCE = 'maintenance';
    case INACTIVE = 'inactive';
}
