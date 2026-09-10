<?php

namespace App\Models\Concerns;

use Illuminate\Support\Facades\Cache;

// Catálogos estáticos (Campus, Career, Semester): cambian solo vía CRUD de
// admin, pero se leen en cada render de páginas con alta concurrencia
// (estudiantes). Se cachean indefinidamente y se invalidan solo cuando el
// modelo cambia.
trait CachesCatalog
{
    public static function cached()
    {
        return Cache::rememberForever(static::class, static fn () => static::all());
    }

    protected static function bootCachesCatalog(): void
    {
        static::saved(fn () => Cache::forget(static::class));
        static::deleted(fn () => Cache::forget(static::class));
        static::restored(fn () => Cache::forget(static::class));
    }
}
