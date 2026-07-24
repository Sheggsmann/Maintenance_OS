<?php

namespace Database\Factories;

use App\Enums\DispatchStatusEnum;
use App\Models\ContractorDispatch;
use App\Models\WorkOrder;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ContractorDispatch>
 */
class ContractorDispatchFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'work_order_id' => WorkOrder::factory(),
            'status' => DispatchStatusEnum::PENDING->value,
            'attempt' => 1,
            'request_payload' => [],
            'response_payload' => null,
            'error' => null,
            'dispatched_at' => null,
        ];
    }
}
