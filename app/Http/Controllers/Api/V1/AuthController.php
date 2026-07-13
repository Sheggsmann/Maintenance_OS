<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\Auth\LoginUserAction;
use App\Actions\Auth\RegisterUserAction;
use App\DTOs\Auth\LoginUserData;
use App\DTOs\Auth\RegisterUserData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Utils\ApiResponse;
use Illuminate\Http\JsonResponse;


class AuthController extends Controller
{
    public function register(RegisterRequest $request, RegisterUserAction $action): JsonResponse
    {
        $result = $action(RegisterUserData::fromRequest($request));

        return ApiResponse::success(
            data: [
                'token' => $result->token,
                'user' => new UserResource($result->user)
            ],
            message: 'Registration successful',
            status: 201
        );
    }

    public function login(LoginRequest $request, LoginUserAction $action): JsonResponse
    {
        $result = $action(LoginUserData::fromRequest($request));

        return ApiResponse::success(
            data: [
                'token' => $result->token,
                'user' => new UserResource($result->user)
            ],
            message: 'Login successful',
            status: 200
        );
    }

    // public function logout(): JsonResponse {

    // }
}
