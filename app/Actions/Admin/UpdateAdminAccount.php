<?php

namespace App\Actions\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UpdateAdminAccount
{
    /**
     * Actualiza nombre/correo de un administrador; la contraseña solo
     * se toca si viene una nueva en $data.
     */
    public function __invoke(User $admin, array $data): User
    {
        $admin->name = $data['name'];
        $admin->email = $data['email'];

        if (! empty($data['password'])) {
            $admin->password = Hash::make($data['password']);
        }

        $admin->save();

        return $admin;
    }
}
