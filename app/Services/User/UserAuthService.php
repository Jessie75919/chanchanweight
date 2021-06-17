<?php

namespace App\Services\User;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserAuthService
{
    public function register(UserInfoDTO $dto)
    {
        $user = User::firstOrCreate([
            'email' => $dto->getEmail(),
        ], [
            'password' => $dto->getPassword(),
            'name' => $dto->getName(),
            'email_verified_at' => $dto->getEmailVerifiedAt()
        ]);

        Auth::login($user);
    }

    public function resetPassword(User $user, string $password)
    {
        $user->forceFill([
            'password' => Hash::make($password),
            'remember_token' => Str::random(60),
        ])->save();
    }

}
