<?php

namespace App\Models;

use Database\Factories\PropertyFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'name',
    'address',
    'city',
    'state',
    'country',
    'postal_code',
    'description',
    'manager_id',
])]
class Property extends Model
{
    /** @use HasFactory<\Database\Factories\PropertyFactory> */
    use HasFactory;
    use SoftDeletes;

    protected static string $factory = PropertyFactory::class;

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    // public function units()
    // {
    //     return $this->hasMany(Unit::class);
    // }
}
