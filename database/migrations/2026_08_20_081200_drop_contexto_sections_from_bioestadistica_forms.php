<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::connection('pgsql')->hasTable('bioestadistica.form_secciones')) {
            return;
        }

        $sectionIds = DB::connection('pgsql')
            ->table('bioestadistica.form_secciones')
            ->where('titulo', 'Contexto')
            ->pluck('id');

        if ($sectionIds->isEmpty()) {
            return;
        }

        if (Schema::connection('pgsql')->hasTable('bioestadistica.fields')) {
            DB::connection('pgsql')
                ->table('bioestadistica.fields')
                ->whereIn('seccion_id', $sectionIds)
                ->delete();
        }

        DB::connection('pgsql')
            ->table('bioestadistica.form_secciones')
            ->whereIn('id', $sectionIds)
            ->delete();
    }

    public function down(): void
    {
        // Intencional: no se recrea la sección Contexto.
    }
};
