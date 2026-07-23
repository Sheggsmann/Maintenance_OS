<?php

namespace App\Enums;

enum WorkOrderStatusEnum: string
{
    case PENDING = 'pending';

    case APPROVED = 'approved';

    case DISPATCHED = 'dispatched';

    case IN_PROGRESS = 'in_progress';

    case COMPLETED = 'completed';

    case CANCELLED = 'cancelled';
}
