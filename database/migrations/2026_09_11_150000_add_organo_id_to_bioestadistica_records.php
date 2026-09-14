<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('pgsql')->table('bioestadistica.records', function (Blueprint $table) {
            $table->foreignId('organo_id')
                ->nullable()
                ->after('establecimiento_id')
                ->constrained('bioestadistica.organos')
                ->restrictOnDelete();
            $table->index('organo_id');
        });

        DB::connection('pgsql')->statement('DROP INDEX IF EXISTS bioestadistica.bio_record_periodo_unique');

        DB::connection('pgsql')->statement('
            CREATE UNIQUE INDEX bio_record_periodo_corte_unique
            ON bioestadistica.records (formulario_id, establecimiento_id, periodo_anio, periodo_mes, organo_id)
            WHERE deleted_at IS NULL AND organo_id IS NOT NULL
        ');

        DB::connection('pgsql')->statement('
            CREATE UNIQUE INDEX bio_record_periodo_sin_corte_unique
            ON bioestadistica.records (formulario_id, establecimiento_id, periodo_anio, periodo_mes)
            WHERE deleted_at IS NULL AND organo_id IS NULL
        ');
    }

    public function down(): void
    {
        DB::connection('pgsql')->statement('DROP INDEX IF EXISTS bioestadistica.bio_record_periodo_corte_unique');
        DB::connection('pgsql')->statement('DROP INDEX IF EXISTS bioestadistica.bio_record_periodo_sin_corte_unique');

        Schema::connection('pgsql')->table('bioestadistica.records', function (Blueprint $table) {
            $table->dropConstrainedForeignId('organo_id');
        });

        DB::connection('pgsql')->statement('
            CREATE UNIQUE INDEX bio_record_periodo_unique
            ON bioestadistica.records (formulario_id, establecimiento_id, periodo_anio, periodo_mes)
            WHERE deleted_at IS NULL
        ');
    }
};
