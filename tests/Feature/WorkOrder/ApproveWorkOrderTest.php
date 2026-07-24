<?php

use App\Enums\ApprovalDecisionEnum;
use App\Enums\ApprovalStageEnum;
use App\Enums\RoleEnum;
use App\Enums\WorkOrderStatusEnum;
use App\Models\User;
use App\Models\WorkOrder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Laravel\Sanctum\Sanctum;


beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
});


it('manager can approve a work order', function () {
    $manager = User::factory()->create();
    $manager->assignRole(RoleEnum::MANAGER->value);

    Sanctum::actingAs($manager);

    $workOrder = WorkOrder::factory()->create([
        'status' => WorkOrderStatusEnum::PENDING->value,
    ]);

    $response = $this->postJson(
        "/api/v1/work-orders/{$workOrder->id}/approve"
    );

    $response->assertOk();

    $this->assertDatabaseHas('approval_histories', [
        'work_order_id' => $workOrder->id,
        'decision' => ApprovalDecisionEnum::APPROVED->value,
        'stage' => ApprovalStageEnum::MANAGER_REVIEW->value,
        'approved_by' => $manager->id,
    ]);

    $this->assertDatabaseHas('work_orders', [
        'id' => $workOrder->id,
        'status' => WorkOrderStatusEnum::APPROVED->value,
    ]);
});
