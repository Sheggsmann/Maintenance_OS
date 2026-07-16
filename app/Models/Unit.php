<?php

namespace App\Models;

use App\Enums\UnitStatusEnum;
use App\Enums\UnitTypeEnum;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'property_id',
    'tenant_id',
    'unit_number',
    'floor',
    'type',
    'status',
    'bedrooms',
    'bathrooms',
    'area',
    'monthly_rent',
    'description',
])]
class Unit extends Model
{
    /** @use HasFactory<\Database\Factories\UnitFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $casts = [
        'type' => UnitTypeEnum::class,
        'status' => UnitStatusEnum::class,
        'montly_rent' => 'decimal:2',
        'area' => 'decimal:2'
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function tenant()
    {
        return $this->belongsTo(User::class, 'tenant_id');
    }
}
