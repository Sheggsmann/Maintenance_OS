<?php

use App\Models\MaintenanceRequest;
use App\Models\Media;

it('ensures maintenance request can have many media', function () {
    $maintenanceRequest = MaintenanceRequest::factory()->create();

    Media::factory()
        ->count(3)
        ->for($maintenanceRequest, 'mediable')
        ->create();

    expect($maintenanceRequest->media)
        ->toHaveCount(3);
});

it('media belongs to maintenance request', function () {
    $maintenanceRequest = MaintenanceRequest::factory()->create();

    $media = Media::factory()
        ->for($maintenanceRequest, 'mediable')
        ->create();

    expect($media->mediable)
        ->toBeInstanceOf(MaintenanceRequest::class);
});
