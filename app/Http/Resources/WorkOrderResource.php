<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'reference' => $this->reference,
            'status' => $this->status,
            'scope_of_work' => $this->scope_of_work,
            'estimated_cost' => $this->estimated_cost,
            'estimated_hours' => $this->estimated_hours,
            'scheduled_for' => $this->scheduled_for,
        ];
    }
}
