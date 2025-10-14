<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserRol extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //$adminRole = Role::create(['name' => 'admin']);
        //$studentRole = Role::create(['name' => 'student']);
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'student', 'guard_name' => 'web']);

    }
}
