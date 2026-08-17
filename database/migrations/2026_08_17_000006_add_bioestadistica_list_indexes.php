<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $db = DB::connection('pgsql');

        $db->statement(
            'CREATE INDEX IF NOT EXISTS bio_records_list_idx
             ON bioestadistica.records (establecimiento_id, estado, periodo_anio, periodo_mes, updated_at DESC)
             WHERE deleted_at IS NULL'
        );
        $db->statement(
            'CREATE INDEX IF NOT EXISTS bio_records_formulario_periodo_idx
             ON bioestadistica.records (formulario_id, periodo_anio, periodo_mes)
             WHERE deleted_at IS NULL'
        );
        $db->statement(
            'CREATE INDEX IF NOT EXISTS hosp_episodios_list_idx
             ON bioestadistica.hosp_episodios (establecimiento_id, periodo_anio, periodo_mes, servicio, fecha_ingreso DESC)
             WHERE deleted_at IS NULL'
        );
        $db->statement(
            'CREATE INDEX IF NOT EXISTS bio_dashboards_user_default_idx
             ON bioestadistica.dashboards (user_id, es_default)
             WHERE deleted_at IS NULL'
        );
        $db->statement(
            'CREATE INDEX IF NOT EXISTS bio_import_jobs_estado_idx
             ON bioestadistica.import_jobs (estado, created_at DESC)'
        );
    }

    public function down(): void
    {
        $db = DB::connection('pgsql');
        $db->statement('DROP INDEX IF EXISTS bioestadistica.bio_records_list_idx');
        $db->statement('DROP INDEX IF EXISTS bioestadistica.bio_records_formulario_periodo_idx');
        $db->statement('DROP INDEX IF EXISTS bioestadistica.hosp_episodios_list_idx');
        $db->statement('DROP INDEX IF EXISTS bioestadistica.bio_dashboards_user_default_idx');
        $db->statement('DROP INDEX IF EXISTS bioestadistica.bio_import_jobs_estado_idx');
    }
};
