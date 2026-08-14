<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::connection('pgsql')->statement(
            'ALTER TABLE bioestadistica.grados_complejidad
             DROP CONSTRAINT IF EXISTS bioestadistica_grados_complejidad_codigo_unique'
        );
        DB::connection('pgsql')->statement(
            'ALTER TABLE bioestadistica.grados_complejidad
             ADD CONSTRAINT bio_grados_codigo_descripcion_unique UNIQUE (codigo, descripcion)'
        );
    }

    public function down(): void
    {
        DB::connection('pgsql')->statement(
            'ALTER TABLE bioestadistica.grados_complejidad
             DROP CONSTRAINT IF EXISTS bio_grados_codigo_descripcion_unique'
        );
        DB::connection('pgsql')->statement(
            'ALTER TABLE bioestadistica.grados_complejidad
             ADD CONSTRAINT bioestadistica_grados_complejidad_codigo_unique UNIQUE (codigo)'
        );
    }
};
