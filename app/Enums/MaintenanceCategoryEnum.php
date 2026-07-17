<?php

namespace App\Enums;

enum MaintenanceCategoryEnum: string
{
    case PLUMBING = 'plumbing';

    case ELECTRICAL = 'electrical';

    case HVAC = 'hvac';

    case PAINTING = 'painting';

    case CARPENTRY = 'carpentry';

    case CLEANING = 'cleaning';

    case SECURITY = 'security';

    case OTHER = 'other';
}
