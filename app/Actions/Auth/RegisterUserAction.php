<?php

namespace App\Actions\Auth;

use App\DTOs\Auth\AuthResult;
use App\DTOs\Auth\RegisterUserData;
use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

final class RegisterUserAction
{
    public function __invoke(RegisterUserData $data): AuthResult
    {
        return DB::transaction(function () use ($data) {

            $user = User::create([
                'name' => $data->name,
                'email' => $data->email,
                'phone' => $data->phone,
                'password' => $data->password,
                'is_active' => true,
            ]);

            $user->assignRole(Role::findByName(RoleEnum::TENANT->value, "sanctum"));

            $token = $user->createToken('auth_token')->plainTextToken;

            return new AuthResult(user: $user, token: $token);
        });
    }
}
