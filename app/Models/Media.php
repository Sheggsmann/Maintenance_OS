<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

#[Fillable([
    'disk',
    'path',
    'original_name',
    'mime_type',
    'extension',
    'size',
    'uploaded_by',
])]
class Media extends Model
{
    /** @use HasFactory<\Database\Factories\MediaFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'size' => 'integer',
        ];
    }

    public function mediable(): MorphTo
    {
        return $this->morphTo();
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'uploaded_by'
        );
    }
}
