<?php

namespace App\Services;

use App\Models\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * Reglas de purgado para los modelos que pasan por la papelera
 * (Period, Semester, Career, Campus, File). Un registro solo se elimina
 * de verdad cuando ya no tiene estudiantes/documentos reales colgando de
 * él; si los tiene, se queda en la papelera indefinidamente en vez de
 * arriesgar perder ese historial.
 */
class TrashPurger
{
    public static function canPurge(string $type, Model $model): bool
    {
        return match ($type) {
            'period', 'semester', 'career', 'campus' => $model->students()->count() === 0,
            'file' => $model->documents()->count() === 0,
        };
    }

    public static function blockedReason(string $type, Model $model): ?string
    {
        if (self::canPurge($type, $model)) {
            return null;
        }

        return $type === 'file'
            ? 'Tiene documentos de estudiantes asociados.'
            : 'Tiene estudiantes registrados.';
    }

    public static function purge(string $type, Model $model): void
    {
        if (! self::canPurge($type, $model)) {
            return;
        }

        if ($type === 'period') {
            File::withTrashed()->where('period_id', $model->id)->get()
                ->each(fn (File $file) => self::deleteFileStorage($file));
        }

        if ($type === 'file') {
            self::deleteFileStorage($model);
        }

        $model->forceDelete();
    }

    private static function deleteFileStorage(File $file): void
    {
        foreach (['file_path', 'example_path'] as $field) {
            if ($file->$field && Storage::disk('local')->exists($file->$field)) {
                Storage::disk('local')->delete($file->$field);
            }
        }
    }
}
