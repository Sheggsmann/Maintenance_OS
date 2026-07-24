<?php

namespace Database\Factories;

use App\Enums\ApprovalDecisionEnum;
use App\Models\ApprovalHistory;
use App\Models\User;
use App\Models\WorkOrder;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ApprovalHistory>
 */
class ApprovalHistoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'work_order_id' => WorkOrder::factory(),
            'approved_by' => User::factory(),
            'decision' => ApprovalDecisionEnum::APPROVED->value,
            'reason' => fake()->sentence(),
            'approved_at' => now(),
        ];
    }
}
