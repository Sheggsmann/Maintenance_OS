<?php

namespace App\Services\Contractor;

use App\Models\WorkOrder;

class ContractorPayloadBuilder
{
    public function build(
        WorkOrder $workOrder
    ): array {

        $request = $workOrder
            ->maintenanceRequest;

        return [
            'reference' => $workOrder->reference,

            'maintenance_request_reference' => $request->reference,

            'property' => [
                'id' => $request->property->id,
                'name' => $request->property->name,
            ],

            'unit' => [
                'id' => $request->unit->id,
                'name' => $request->unit->name,
            ],

            'issue' => [
                'title' => $request->title,
                'description' => $request->description,
                'category' => $request->category,
                'priority' => $request->priority,

            ],

            'scope_of_work' => $workOrder->scope_of_work,

            'estimated_cost' => $workOrder->estimated_cost,

            'estimated_hours' => $workOrder->estimated_hours,

            'scheduled_for' => optional($workOrder->scheduled_for)?->toIso8601String(),

            'media' => $request
                ->media
                ->map(fn($media) => [
                    'url' => asset('storage/' . $media->path),
                    'type' => $media->mime_type,
                ])
                ->values()
                ->all(),
        ];
    }
}
