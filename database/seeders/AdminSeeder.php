<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear un usuario que será administrador
        /*$userId = DB::table('users')->insertGetId([
            'name' => 'Administrador Principal',
            'email' => 'admin@example.com',
            'password' => Hash::make('supersecret'), // contraseña encriptada
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Asignar el rol de administrador al usuario creado
        $user = User::find($userId);
        $user->assignRole('admin');*/

        $user = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrador Principal',
                'password' => Hash::make('supersecret'),
            ]
        );

        $user->assignRole('admin');

    }
}
