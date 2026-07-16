<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UnitResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'unit_number' => $this->unit_number,
            'floor' => $this->floor,
            'type' => $this->type,
            'status' => $this->status,
            'bedrooms' => $this->bedrooms,

            'bathrooms' => $this->bathrooms,

            'area' => $this->area,

            'monthly_rent' => $this->monthly_rent,
            'description' => $this->description,

            'property' => [
                'id' => $this->property->id,
                'name' => $this->property->name,
            ],

            'tenant' => $this->tenant
                ? [
                    'id' => $this->tenant->id,
                    'name' => $this->tenant->name,
                ]
                : null,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
