<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Retira el corte legado estructura_* / establecimiento_servicios.
 * El enlace establecimiento↔órgano queda en establecimiento_organos (UI organigrama).
 */
return new class extends Migration
{
    public function up(): void
    {
        // Datos de prueba / asociaciones legacy (records con corte ya estaban en 0 en dev).
        DB::connection('pgsql')->table('bioestadistica.records')->update([
            'estructura_departamento_id' => null,
            'estructura_servicio_id' => null,
        ]);

        DB::connection('pgsql')->statement('DROP INDEX IF EXISTS bioestadistica.bio_record_periodo_corte_unique');
        DB::connection('pgsql')->statement('DROP INDEX IF EXISTS bioestadistica.bio_record_periodo_sin_corte_unique');

        Schema::connection('pgsql')->table('bioestadistica.records', function (Blueprint $table) {
            if (Schema::connection('pgsql')->hasColumn('bioestadistica.records', 'estructura_servicio_id')) {
                $table->dropConstrainedForeignId('estructura_servicio_id');
            }
            if (Schema::connection('pgsql')->hasColumn('bioestadistica.records', 'estructura_departamento_id')) {
                $table->dropConstrainedForeignId('estructura_departamento_id');
            }
        });

        DB::connection('pgsql')->statement('
            CREATE UNIQUE INDEX bio_record_periodo_unique
            ON bioestadistica.records (formulario_id, establecimiento_id, periodo_anio, periodo_mes)
            WHERE deleted_at IS NULL
        ');

        Schema::connection('pgsql')->dropIfExists('bioestadistica.establecimiento_servicios');
        Schema::connection('pgsql')->dropIfExists('bioestadistica.estructura_servicios');
        Schema::connection('pgsql')->dropIfExists('bioestadistica.estructura_departamentos');
    }

    public function down(): void
    {
        // No se recrea el modelo legado; rollback solo deja constancia.
        throw new RuntimeException('Irreversible: estructura_* fue retirado a favor del organigrama.');
    }
};
