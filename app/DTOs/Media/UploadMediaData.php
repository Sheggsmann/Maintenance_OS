<?php

namespace App\DTOs\MaintenanceRequest;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

class UploadMediaData
{
    public function __construct(
        public readonly Model $mediable,
        public readonly UploadedFile $file,
        public readonly User $uploadedBy,
    ) {}
}
