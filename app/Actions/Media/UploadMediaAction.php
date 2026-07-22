<?php

namespace App\Actions\Media;

use App\Models\Media;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

final class UploadMediaAction
{
    public function __invoke(
        Model $model,
        UploadedFile $file
    ): Media {

        $path = $file->store(
            'maintenance-requests',
            'public'
        );

        return $model->media()->create([
            'disk' => 'public',
            'path' => $path,
            'filename' => $file->hashName(),
            'original_name' => $file->getClientOriginalName(),
            'extension' => $file->getClientOriginalExtension(),
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'uploaded_by' => auth()->id()
        ]);
    }
}
