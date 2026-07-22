<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MaintenanceRequestResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [

            'id' => $this->id,

            'title' => $this->title,

            'description' => $this->description,

            'priority' => $this->priority,

            'status' => $this->status,

            'category' => $this->category,

            'reported_at' => $this->reported_at,

            'started_at' => $this->started_at,

            'completed_at' => $this->completed_at,

            'property' => [
                'id' => $this->property->id,
                'name' => $this->property->name,
            ],

            'unit' => [
                'id' => $this->unit->id,
                'unit_number' => $this->unit->unit_number,
            ],

            'tenant' => [
                'id' => $this->tenant->id,
                'name' => $this->tenant->name,
            ],

            'contractor' => $this->contractor
                ? [
                    'id' => $this->contractor->id,
                    'name' => $this->contractor->name,
                ]
                : null,

            'media' => MediaResource::collection(
                $this->whenLoaded('media')
            ),

            'created_at' => $this->created_at,

            'updated_at' => $this->updated_at,

        ];
    }
}
