<?php

namespace Database\Factories;

use App\Enums\WorkOrderStatusEnum;
use App\Models\MaintenanceRequest;
use App\Models\User;
use App\Models\WorkOrder;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WorkOrder>
 */
class WorkOrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'maintenance_request_id' => MaintenanceRequest::factory(),
            'created_by' => User::factory(),
            'reference' => fake()->unique()->bothify('WO-########'),
            'status' => WorkOrderStatusEnum::PENDING->value,
        ];
    }
}
