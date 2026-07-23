<?php

namespace App\Models;

use App\Enums\MaintenanceCategoryEnum;
use App\Enums\MaintenancePriorityEnum;
use App\Enums\MaintenanceStatusEnum;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'property_id',
    'unit_id',
    'tenant_id',
    'assigned_to',
    'title',
    'description',
    'priority',
    'status',
    'category',
    'reported_at',
    'started_at',
    'completed_at',
])]
class MaintenanceRequest extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $casts = [
        'priority' => MaintenancePriorityEnum::class,
        'status' => MaintenanceStatusEnum::class,
        'category' => MaintenanceCategoryEnum::class,
        'reported_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function tenant()
    {
        return $this->belongsTo(User::class, 'tenant_id');
    }

    public function contractor()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function media(): MorphMany
    {
        return $this->morphMany(
            Media::class,
            'mediable'
        );
    }

    public function workOrder(): HasOne
    {
        return $this->hasOne(WorkOrder::class);
    }
}
