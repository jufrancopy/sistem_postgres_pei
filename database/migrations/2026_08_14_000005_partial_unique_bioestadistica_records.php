<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::connection('pgsql')->statement(
            'ALTER TABLE bioestadistica.records DROP CONSTRAINT IF EXISTS bio_record_periodo_unique'
        );
        DB::connection('pgsql')->statement(
            'CREATE UNIQUE INDEX bio_record_periodo_unique
             ON bioestadistica.records (formulario_id, establecimiento_id, periodo_anio, periodo_mes)
             WHERE deleted_at IS NULL'
        );
    }

    public function down(): void
    {
        DB::connection('pgsql')->statement('DROP INDEX IF EXISTS bioestadistica.bio_record_periodo_unique');
        DB::connection('pgsql')->statement(
            'ALTER TABLE bioestadistica.records
             ADD CONSTRAINT bio_record_periodo_unique
             UNIQUE (formulario_id, establecimiento_id, periodo_anio, periodo_mes)'
        );
    }
};
