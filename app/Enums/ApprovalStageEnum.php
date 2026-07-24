<?php

namespace App\Enums;

enum ApprovalStageEnum: string
{
    case MANAGER_REVIEW = 'manager_review';
    case ADMIN_REVIEW = 'admin_review';
    case FINANCE_REVIEW = 'finance_review';
}
