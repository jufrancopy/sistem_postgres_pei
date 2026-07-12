<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("CREATE TABLE IF NOT EXISTS planificacion.marco_tipos (
            id        BIGSERIAL PRIMARY KEY,
            clave     VARCHAR(50)  NOT NULL UNIQUE,
            label     VARCHAR(100) NOT NULL,
            color     VARCHAR(30)  NOT NULL DEFAULT 'secondary',
            activo    BOOLEAN      NOT NULL DEFAULT TRUE,
            created_at TIMESTAMPTZ,
            updated_at TIMESTAMPTZ
        )");

        // Sembrar los tipos existentes
        $ahora = now();
        $tipos = [
            ['clave' => 'pnd',     'label' => 'PND 2050',              'color' => 'danger'],
            ['clave' => 'ods',     'label' => 'ODS 2030',              'color' => 'success'],
            ['clave' => 'mecip',   'label' => 'MECIP 2015',            'color' => 'warning'],
            ['clave' => 'pgn',     'label' => 'PGN',                   'color' => 'dark'],
            ['clave' => 'pam',     'label' => 'Plan de Alcance Medio', 'color' => 'purple'],
            ['clave' => 'general', 'label' => 'General',               'color' => 'secondary'],
        ];

        foreach ($tipos as $t) {
            DB::table('planificacion.marco_tipos')->insertOrIgnore(
                array_merge($t, ['created_at' => $ahora, 'updated_at' => $ahora])
            );
        }
    }

    public function down(): void
    {
        DB::statement("DROP TABLE IF EXISTS planificacion.marco_tipos CASCADE");
    }
};
