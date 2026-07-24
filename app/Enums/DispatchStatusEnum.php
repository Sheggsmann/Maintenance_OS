<?php

namespace App\Enums;

enum DispatchStatusEnum: string
{
    case PENDING = 'pending';
    case SENT = 'sent';
    case FAILED = 'failed';
    case COMPLETED = 'completed';
}
