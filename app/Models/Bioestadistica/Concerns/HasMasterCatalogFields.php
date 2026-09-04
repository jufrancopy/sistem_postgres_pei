<?php

namespace App\Models\Bioestadistica\Concerns;

use Illuminate\Support\Str;

trait HasMasterCatalogFields
{
    public static function normalizeNombre(string $nombre): string
    {
        return Str::lower(Str::ascii(trim($nombre)));
    }
}
