<?php

namespace App\Models;

use App\Enums\WorkOrderStatusEnum;
use DomainException;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

#[Fillable([
    'maintenance_request_id',

    'assigned_contractor_id',

    'created_by',

    'reference',

    'scope_of_work',

    'estimated_cost',

    'estimated_hours',

    'scheduled_for',

    'status',

    'approved_at',

    'dispatched_at',

    'started_at',

    'completed_at',

    'cancelled_at',
])]
class WorkOrder extends Model
{
    /** @use HasFactory<\Database\Factories\WorkOrderFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'scheduled_for' => 'datetime',

            'approved_at' => 'datetime',

            'dispatched_at' => 'datetime',

            'started_at' => 'datetime',

            'completed_at' => 'datetime',

            'cancelled_at' => 'datetime',

            'status' => WorkOrderStatusEnum::class,
        ];
    }

    public function maintenanceRequest(): BelongsTo
    {
        return $this->belongsTo(MaintenanceRequest::class);
    }

    public function contractor(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'assigned_contractor_id'
        );
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }

    public function media(): MorphMany
    {
        return $this->morphMany(
            Media::class,
            'mediable'
        );
    }

    public function approvals(): HasMany
    {
        return $this->hasMany(
            ApprovalHistory::class
        );
    }

    public function dispatches(): HasMany
    {
        return $this->hasMany(
            ContractorDispatch::class
        );
    }

    public function approve(): void
    {
        if ($this->status !== WorkOrderStatusEnum::PENDING) {
            throw new DomainException(
                'Only pending work orders can be approved.'
            );
        }

        $this->update([
            'status' => WorkOrderStatusEnum::APPROVED->value,
            'approved_at' => now(),
        ]);
    }

    public function dispatch(): void
    {
        if ($this->status !== WorkOrderStatusEnum::APPROVED) {
            throw new DomainException(
                'Only approved work orders can be dispatched.'
            );
        }

        $this->update([
            "status" => WorkOrderStatusEnum::DISPATCHED->value,
            "dispatched_at" => now(),
        ]);
    }

    public function accepted(): void
    {
        $this->update([
            'status' => WorkOrderStatusEnum::ACCEPTED->value,
        ]);
    }

    public function start(): void
    {
        if ($this->status !== WorkOrderStatusEnum::DISPATCHED) {
            throw new DomainException('Only dispatched work orders can start');
        }

        $this->update([
            'status' => WorkOrderStatusEnum::IN_PROGRESS->value,
            'started_at' => now(),
        ]);
    }

    public function complete(): void
    {
        if ($this->status !== WorkOrderStatusEnum::IN_PROGRESS) {
            throw new DomainException(
                'Only work orders in progress can be completed.'
            );
        }

        $this->update([
            'status' => WorkOrderStatusEnum::COMPLETED->value,
            'completed_at' => now(),
        ]);
    }

    public function cancel(): void
    {
        if ($this->status === WorkOrderStatusEnum::COMPLETED) {
            throw new DomainException(
                'Completed work orders cannot be cancelled.'
            );
        }

        $this->update([
            'status' => WorkOrderStatusEnum::CANCELLED->value,
            'cancelled_at' => now(),
        ]);
    }
}
