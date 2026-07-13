<?php

namespace App\Actions\Auth;

use App\DTOs\Auth\AuthResult;
use App\DTOs\Auth\LoginUserData;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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

        $token = $user->createToken('auth_token')->plainTextToken;

        Log::info("\nLogin User", compact('user', 'token'));

        return new AuthResult(user: $user, token: $token);
    }
}
