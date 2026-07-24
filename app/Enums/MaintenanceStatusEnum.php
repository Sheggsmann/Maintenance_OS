<?php

namespace App\Enums;

enum MaintenanceStatusEnum: string
{
    case OPEN = 'open';

    case APPROVED = 'approved';

    case ASSIGNED = 'assigned';

    case IN_PROGRESS = 'in_progress';

    case ON_HOLD = 'on_hold';

    case COMPLETED = 'completed';

    case CANCELLED = 'cancelled';
}
