<?php

namespace App\Console\Commands;

use App\Models\Campus;
use App\Models\Career;
use App\Models\File;
use App\Models\Period;
use App\Models\Semester;
use App\Services\TrashPurger;
use Illuminate\Console\Command;

class PurgeTrash extends Command
{
    protected $signature = 'trash:purge';

    protected $description = 'Elimina permanentemente los registros en la papelera con más de 30 días, siempre que no tengan estudiantes o documentos ligados.';

    private const TYPES = [
        'period'   => Period::class,
        'semester' => Semester::class,
        'career'   => Career::class,
        'campus'   => Campus::class,
        'file'     => File::class,
    ];

    public function handle(): int
    {
        $purged  = 0;
        $skipped = 0;

        foreach (self::TYPES as $type => $modelClass) {
            $modelClass::onlyTrashed()
                ->where('deleted_at', '<=', now()->subDays(30))
                ->get()
                ->each(function ($model) use ($type, &$purged, &$skipped) {
                    if (TrashPurger::canPurge($type, $model)) {
                        TrashPurger::purge($type, $model);
                        $purged++;
                    } else {
                        $skipped++;
                    }
                });
        }

        $this->info("Purga completada: {$purged} eliminados, {$skipped} conservados por tener información ligada.");

        return self::SUCCESS;
    }
}
