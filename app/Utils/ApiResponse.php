<?php

namespace App\Utils;

use Illuminate\Http\JsonResponse;

final class ApiResponse
{
    public static function success(
        mixed $data = null,
        string $message = 'Success',
        int $status = 200
    ): JsonResponse {
        if ($data instanceof \Illuminate\Http\Resources\Json\ResourceCollection) {
            $response = $data->response()->getData(true);
            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $response['data'],
                'links' => $response['links'] ?? null,
                'meta' => $response['meta'] ?? null
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $status);
    }

    public static function error(
        string $message,
        mixed $errors = null,
        int $status = 400
    ) {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors
        ], $status);
    }
}
