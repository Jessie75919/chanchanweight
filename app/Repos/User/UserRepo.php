<?php

namespace App\Repos\User;

use App\Models\User;

class UserRepo
{

    public function updateInfo(User $user, ?string $name, ?string $birthday, ?string $gender)
    {
        $user->update([
            'name' => $name,
            'birth' => $birthday,
            'gender' => $gender
        ]);
    }
}
