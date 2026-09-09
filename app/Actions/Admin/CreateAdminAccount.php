<?php

namespace App\Actions\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateAdminAccount
{
    /**
     * Crea una cuenta de usuario con rol de administrador.
     */
    public function __invoke(array $data): User
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'created_by' => auth()->id(),
        ]);

        $user->assignRole('admin');

        return $user;
    }
}
