<?php

namespace App\DTOs\Auth;

use App\Enums\RoleEnum;
use App\Http\Requests\Auth\RegisterRequest;

final readonly class RegisterUserData
{
    public function __construct(
        public string $name,
        public string $email,
        public ?string $phone,
        public string $password,
        public RoleEnum $role,
    ) {}

    public static function fromRequest(RegisterRequest $request): self
    {
        return new self(
            name: $request->string('name')->toString(),
            email: $request->string("email")->toString(),
            phone: $request->string("phone"),
            password: $request->string("password")->toString(),
            role: RoleEnum::from($request->string("role")->toString())
        );
    }
}
