<?php

// use App\Enums\RoleEnum;
// use App\Enums\WorkOrderStatusEnum;
// use App\Jobs\DispatchWorkOrderJob;
// use App\Models\User;
// use App\Models\WorkOrder;
// use Database\Seeders\RolesAndPermissionsSeeder;
// use Illuminate\Support\Facades\Queue;
// use Laravel\Sanctum\Sanctum;

// beforeEach(function () {
//     $this->seed(RolesAndPermissionsSeeder::class);
// });

// it("dispatches a job after approval", function () {
//     Queue::fake();

//     $manager = User::factory()->create();
//     $manager->assignRole(RoleEnum::MANAGER->value);

//     Sanctum::actingAs($manager);

//     $workOrder = WorkOrder::factory()->create([
//         'status' => WorkOrderStatusEnum::PENDING->value,
//     ]);

//     $this
//         ->postJson("/api/v1/maintenance-request/{$workOrder->id}/approve")
//         ->assertOk();

//     Queue::assertPushed(DispatchWorkOrderJob::class);
// });
