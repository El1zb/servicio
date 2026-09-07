<?php

namespace Database\Seeders;

use App\Models\File;
use App\Models\Period;
use Illuminate\Database\Seeder;

class FileSeeder extends Seeder
{
    public function run(): void
    {
        $documentNames = [
            'Carta de Presentación',
            'Reporte Bimestral 1',
            'Reporte Bimestral 2',
            'Reporte Final',
            'Carta de Terminación',
            'Evaluación del Jefe Inmediato',
        ];

        foreach (Period::all() as $period) {
            $count = random_int(2, 4);
            $start = \Carbon\Carbon::parse($period->start_date);

            for ($i = 0; $i < $count; $i++) {
                $name = $documentNames[array_rand($documentNames)];

                File::firstOrCreate(
                    ['period_id' => $period->id, 'name' => $name],
                    [
                        'limit_date' => $start->copy()->addWeeks(($i + 1) * 3),
                        'max_size'   => 10240,
                    ]
                );
            }
        }
    }
}
