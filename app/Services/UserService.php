<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;

class UserService
{
    public function createUser(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'role' => $data['role'],
        ]);
    }

    public function updateName(User $user, string $name): User
    {
        $user->update(['name' => $name]);

        return $user;
    }
}
