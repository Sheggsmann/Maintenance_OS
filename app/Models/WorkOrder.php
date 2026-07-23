<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
}
