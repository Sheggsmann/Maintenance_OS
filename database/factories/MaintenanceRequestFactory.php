<?php

namespace Database\Factories;

use App\Enums\MaintenanceCategoryEnum;
use App\Enums\MaintenancePriorityEnum;
use App\Enums\MaintenanceStatusEnum;
use App\Models\MaintenanceRequest;
use App\Models\Property;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MaintenanceRequest>
 */
class MaintenanceRequestFactory extends Factory
{
    public function definition(): array
    {
        return [
            'property_id' => Property::factory(),

            'unit_id' => Unit::factory(),

            'tenant_id' => User::factory(),

            'assigned_to' => null,

            'title' => fake()->sentence(4),

            'description' => fake()->paragraph(),

            'priority' => fake()->randomElement(MaintenancePriorityEnum::cases()),

            'status' => MaintenanceStatusEnum::OPEN,

            'category' => fake()->randomElement(MaintenanceCategoryEnum::cases()),

            'reported_at' => now(),
        ];
    }
}
