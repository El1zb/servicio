<?php

namespace App\Actions\Admin;

use App\Models\User;

class DeleteAdminAccount
{
    public function __invoke(User $admin): void
    {
        $admin->delete();
    }
}
