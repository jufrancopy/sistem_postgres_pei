<?php

namespace App\Application\Bioestadistica\Dictionary;

use App\Models\Bioestadistica\VariableDetalle;
use Illuminate\Support\Str;

class DictionaryCodes
{
    public static function slug(VariableDetalle $detalle): string
    {
        $detalle->loadMissing('variable');

        return Str::limit(
            Str::upper(Str::slug("VAR_{$detalle->variable->codigo}_{$detalle->nombre}", '_')),
            80,
            ''
        );
    }

    public static function fieldCode(VariableDetalle $detalle): string
    {
        return Str::limit(Str::lower(self::slug($detalle)), 100, '');
    }
}
