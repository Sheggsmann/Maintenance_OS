<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

#[Fillable([
    'work_order_id',
    'approved_by',
    'decision',
    'reason',
    'approved_at'
])]
class ApprovalHistory extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'approved_at' => 'datetime',
        ];
    }

    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function approver()
    {
        return $this->belongsTo(
            User::class,
            'approved_by'
        );
    }
}
