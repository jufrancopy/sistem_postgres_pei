<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Elimina secciones vacías duplicadas de SP12 (cabecera "VIH, tuberculosis y ENT" repetida).
 */
return new class extends Migration
{
    public function up(): void
    {
        $formId = DB::table('bioestadistica.formularios')->where('codigo', 'SP12')->value('id');
        if (! $formId) {
            return;
        }

        $sections = DB::table('bioestadistica.form_secciones')
            ->where('formulario_id', $formId)
            ->whereNull('deleted_at')
            ->where('titulo', 'VIH, tuberculosis y ENT')
            ->orderBy('id')
            ->get(['id']);

        if ($sections->count() < 2) {
            return;
        }

        $keepId = null;
        foreach ($sections as $section) {
            $fieldCount = DB::table('bioestadistica.fields')
                ->where('seccion_id', $section->id)
                ->whereNull('deleted_at')
                ->count();
            if ($fieldCount > 0) {
                $keepId = $section->id;
                break;
            }
        }
        $keepId ??= $sections->first()->id;

        foreach ($sections as $section) {
            if ((int) $section->id === (int) $keepId) {
                continue;
            }
            DB::table('bioestadistica.fields')
                ->where('seccion_id', $section->id)
                ->whereNull('deleted_at')
                ->update(['deleted_at' => now()]);
            DB::table('bioestadistica.form_secciones')
                ->where('id', $section->id)
                ->whereNull('deleted_at')
                ->update(['deleted_at' => now()]);
        }
    }

    public function down(): void
    {
        // Irreversible: no restauramos secciones vacías duplicadas.
    }
};
