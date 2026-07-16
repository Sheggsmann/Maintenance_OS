<?php

namespace App\Enums;

enum UnitTypeEnum: string
{
    case APARTMENT = 'apartment';
    case DUPLEX = 'duplex';
    case OFFICE = 'office';
    case SHOP = 'shop';
    case HOSTEL = 'hostel';
    case WAREHOUSE = 'warehouse';
    case OTHER = 'other';
}
