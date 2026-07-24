<?php

namespace App\Enums;

enum ApprovalDecisionEnum: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
}
