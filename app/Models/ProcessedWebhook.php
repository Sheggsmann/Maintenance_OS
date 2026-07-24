<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


#[Fillable([
    'event_id',
    'provider',
    'reference',
    'status',
    'payload_hash',
    'processed_at',
])]
class ProcessedWebhook extends Model
{
    /** @use HasFactory<\Database\Factories\ProcessedWebhookFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'processed_at' => 'datetime',
        ];
    }
}
