<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


#[Fillable([
    'work_order_id',
    'external_reference',
    'status',
    'attempt',
    'request_payload',
    'response_payload',
    'error',
    'dispatched_at',
])]
class ContractorDispatch extends Model
{
    /** @use HasFactory<\Database\Factories\ContractorDispatchFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'request_payload' => 'array',
            'response_payload' => 'array',
            'dispatched_at' => 'datetime',
        ];
    }

    public function workOrder(): BelongsTo
    {
        return $this->belongsTo(
            WorkOrder::class
        );
    }
}
