<?php

namespace App\DTOs\MaintenanceRequest;

use App\Enums\MaintenanceCategoryEnum;
use App\Enums\MaintenancePriorityEnum;
use Illuminate\Http\Request;
use Spatie\LaravelData\Data;

final class CreateMaintenanceRequestData extends Data
{
    public function __construct(
        public readonly int $unit_id,
        public readonly string $title,
        public readonly string $description,
        public readonly  ?MaintenancePriorityEnum $priority,
        public ?MaintenanceCategoryEnum $category,
        public readonly ?array $media = [],
    ) {}

    // public static function from(Request $request): self
    // {
    //     return new self(
    //         unit_id: $request->integer('unit_id'),
    //         title: $request->string('title'),
    //         description: $request->string('description'),
    //         priority: $request->string('priority'),
    //         category: $request->string('category')
    //         media: $request->file('media', []),
    //     );
    // }
}
