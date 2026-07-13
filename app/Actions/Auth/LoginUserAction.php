<?php

namespace App\Actions\Auth;

use App\DTOs\Auth\AuthResult;
use App\DTOs\Auth\LoginUserData;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Hash;

final class LoginUserAction
{
    public function __invoke(LoginUserData $data): AuthResult
    {
        $user = User::where('email', $data->email)->first();

        if (!$user || !Hash::check($data->password, $user->password)) {
            throw new AuthenticationException("Invalid credentials.");
        }

        if (!$user->is_active) {
            throw new AuthorizationException("User is inactive.");
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return new AuthResult(user: $user, token: $token);
    }
}
