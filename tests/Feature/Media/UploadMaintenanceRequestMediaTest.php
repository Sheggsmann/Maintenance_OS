<?php

use App\Enums\MaintenanceCategoryEnum;
use App\Enums\MaintenancePriorityEnum;
use App\Enums\MaintenanceStatusEnum;
use App\Enums\RoleEnum;
use App\Enums\UnitStatusEnum;
use App\Models\MaintenanceRequest;
use App\Models\Media;
use App\Models\Property;
use App\Models\Unit;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $this->seed(RolesAndPermissionsSeeder::class);
});

it("lets tenant create maintenance request with media", function () {
    Storage::fake('public');

    $tenant = User::factory()->create();
    $tenant->assignRole(RoleEnum::TENANT->value);

    Sanctum::actingAs($tenant);

    $property = Property::factory()->create();
    $unit = Unit::factory()->create([
        'property_id' => $property->id,
        'status' => UnitStatusEnum::OCCUPIED->value,
        'tenant_id' => $tenant->id
    ]);

    $response = $this->postJson('/api/v1/maintenance-requests', [
        'property_id' => $property->id,
        'unit_id' => $unit->id,
        'title' => 'Kitchen Sink',
        'description' => 'Water is leaking.',
        'priority' => MaintenancePriorityEnum::HIGH->value,
        'category' => MaintenanceCategoryEnum::PLUMBING->value,
        'media' => [
            UploadedFile::fake()->image('leak.jpg'),
            UploadedFile::fake()->image('sink.jpg'),
        ],
    ]);

    $response->assertCreated();

    Storage::disk('public')->assertExists(
        'media/maintenance-requests/1/leak.jpg'
    );

    expect(Media::count())->toBe(2);
});

it("stores uploaded files against the maintenance request", function () {
    Storage::fake('public');

    $tenant = User::factory()->create();
    $tenant->assignRole(RoleEnum::TENANT->value);

    Sanctum::actingAs($tenant);

    $property = Property::factory()->create();
    $unit = Unit::factory()->create([
        'property_id' => $property->id,
        'status' => UnitStatusEnum::OCCUPIED->value,
        'tenant_id' => $tenant->id
    ]);

    $response = $this->postJson('/api/v1/maintenance-requests', [
        'property_id' => $property->id,
        'unit_id' => $unit->id,
        'title' => 'Kitchen Sink',
        'description' => 'Water is leaking.',
        'priority' => MaintenancePriorityEnum::HIGH->value,
        'category' => MaintenanceCategoryEnum::PLUMBING->value,
        'media' => [
            UploadedFile::fake()->image('leak.jpg'),
            UploadedFile::fake()->image('sink.jpg'),
        ],
    ]);

    $response->assertCreated();

    $maintenanceRequest = MaintenanceRequest::first();

    expect($maintenanceRequest->media)
        ->toHaveCount(2);
});

// it("lets tenant create maintenance request with media", function () {
//     Storage::fake('public');

//     $tenant = User::factory()->create();
//     $tenant->assignRole(RoleEnum::TENANT->value);

//     Sanctum::actingAs($tenant);

//     $property = Property::factory()->create();
//     $unit = Unit::factory()->create([
//         'property_id' => $property->id,
//     ]);

//     $response = $this->postJson('/api/v1/maintenance-requests', [
//         'property_id' => $property->id,
//         'unit_id' => $unit->id,
//         'title' => 'Kitchen Sink',
//         'description' => 'Water is leaking.',
//         'priority' => MaintenancePriorityEnum::HIGH->value,
//         'media' => [
//             UploadedFile::fake()->create('virus.exe')
//         ],
//     ]);

//     $response->assertUnprocessable();
// });
