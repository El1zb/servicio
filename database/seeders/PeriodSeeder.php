<?php

namespace Database\Seeders;

use App\Models\Period;
use App\Models\Semester;
use Illuminate\Database\Seeder;

class PeriodSeeder extends Seeder
{
    public function run(): void
    {
        $semesterIds = Semester::pluck('id')->all();

        $periods = [
            ['name' => 'Enero - Junio 2025', 'start_date' => '2025-01-06', 'end_date' => '2025-06-30', 'is_active' => false],
            ['name' => 'Agosto - Diciembre 2025', 'start_date' => '2025-08-04', 'end_date' => '2025-12-12', 'is_active' => false],
            ['name' => 'Enero - Junio 2026', 'start_date' => '2026-01-05', 'end_date' => '2026-06-26', 'is_active' => false],
            ['name' => 'Agosto - Diciembre 2026', 'start_date' => '2026-08-03', 'end_date' => '2026-12-11', 'is_active' => true],
            ['name' => 'Enero - Junio 2027', 'start_date' => '2027-01-04', 'end_date' => '2027-06-25', 'is_active' => false],
        ];

        foreach ($periods as $data) {
            $period = Period::firstOrCreate(['name' => $data['name']], $data);

            $count = count($semesterIds) ? random_int(2, min(4, count($semesterIds))) : 0;
            if ($count > 0) {
                $period->semesters()->sync((array) array_rand(array_flip($semesterIds), $count));
            }
        }
    }
}
