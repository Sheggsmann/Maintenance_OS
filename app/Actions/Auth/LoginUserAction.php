<?php

namespace App\Actions\Auth;

use App\DTOs\Auth\AuthResult;
use App\DTOs\Auth\LoginUserData;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;

final class LoginUserAction
{
    public function __invoke(LoginUserData $data): AuthResult
    {
        if (!Auth::attempt([
            'email' => $data->email,
            'password' => $data->password
        ])) {
            throw new AuthenticationException("Invalid credentials.");
        }

        $user = Auth::user();

        if (!$user->is_active) {
            throw new AuthorizationException("User is inactive.");
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return new AuthResult(user: $user, token: $token);
    }
}
