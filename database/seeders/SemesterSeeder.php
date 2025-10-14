<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Semester;

class SemesterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $semesters = [
            ['name' => '6', 'is_active' => true],
            ['name' => '7', 'is_active' => true],
            ['name' => '8', 'is_active' => true],
            ['name' => '9', 'is_active' => true],
            ['name' => '10', 'is_active' => true],
            ['name' => '11', 'is_active' => true],
            ['name' => '12', 'is_active' => true],
        ];

        Semester::insert($semesters);
    }
}
