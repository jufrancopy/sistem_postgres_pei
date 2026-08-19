<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('pgsql')->create('bioestadistica.establecimiento_servicios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('establecimiento_id')->constrained('bioestadistica.establecimientos')->cascadeOnDelete();
            $table->foreignId('departamento_id')->constrained('bioestadistica.estructura_departamentos')->restrictOnDelete();
            $table->foreignId('servicio_id')->constrained('bioestadistica.estructura_servicios')->restrictOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['establecimiento_id', 'departamento_id', 'servicio_id'], 'bio_est_serv_unique');
            $table->index('establecimiento_id');
        });

        DB::connection('pgsql')->statement("
            INSERT INTO bioestadistica.establecimiento_servicios
                (establecimiento_id, departamento_id, servicio_id, created_at, updated_at)
            SELECT id, estructura_departamento_id, estructura_servicio_id, NOW(), NOW()
            FROM bioestadistica.establecimientos
            WHERE estructura_departamento_id IS NOT NULL
              AND estructura_servicio_id IS NOT NULL
              AND deleted_at IS NULL
        ");

        Schema::connection('pgsql')->table('bioestadistica.establecimientos', function (Blueprint $table) {
            $table->dropConstrainedForeignId('estructura_servicio_id');
            $table->dropConstrainedForeignId('estructura_departamento_id');
        });

        Schema::connection('pgsql')->table('bioestadistica.records', function (Blueprint $table) {
            $table->foreignId('estructura_departamento_id')
                ->nullable()
                ->constrained('bioestadistica.estructura_departamentos')
                ->restrictOnDelete();
            $table->foreignId('estructura_servicio_id')
                ->nullable()
                ->constrained('bioestadistica.estructura_servicios')
                ->restrictOnDelete();
        });

        DB::connection('pgsql')->statement('DROP INDEX IF EXISTS bioestadistica.bio_record_periodo_unique');
        DB::connection('pgsql')->statement("
            CREATE UNIQUE INDEX bio_record_periodo_corte_unique
            ON bioestadistica.records (
                formulario_id, establecimiento_id, periodo_anio, periodo_mes,
                estructura_departamento_id, estructura_servicio_id
            )
            WHERE deleted_at IS NULL AND estructura_servicio_id IS NOT NULL
        ");
        DB::connection('pgsql')->statement("
            CREATE UNIQUE INDEX bio_record_periodo_sin_corte_unique
            ON bioestadistica.records (formulario_id, establecimiento_id, periodo_anio, periodo_mes)
            WHERE deleted_at IS NULL AND estructura_servicio_id IS NULL
        ");
    }

    public function down(): void
    {
        DB::connection('pgsql')->statement('DROP INDEX IF EXISTS bioestadistica.bio_record_periodo_corte_unique');
        DB::connection('pgsql')->statement('DROP INDEX IF EXISTS bioestadistica.bio_record_periodo_sin_corte_unique');
        DB::connection('pgsql')->statement("
            CREATE UNIQUE INDEX bio_record_periodo_unique
            ON bioestadistica.records (formulario_id, establecimiento_id, periodo_anio, periodo_mes)
            WHERE deleted_at IS NULL
        ");

        Schema::connection('pgsql')->table('bioestadistica.records', function (Blueprint $table) {
            $table->dropConstrainedForeignId('estructura_servicio_id');
            $table->dropConstrainedForeignId('estructura_departamento_id');
        });

        Schema::connection('pgsql')->table('bioestadistica.establecimientos', function (Blueprint $table) {
            $table->foreignId('estructura_departamento_id')
                ->nullable()
                ->constrained('bioestadistica.estructura_departamentos')
                ->nullOnDelete();
            $table->foreignId('estructura_servicio_id')
                ->nullable()
                ->constrained('bioestadistica.estructura_servicios')
                ->nullOnDelete();
        });

        Schema::connection('pgsql')->dropIfExists('bioestadistica.establecimiento_servicios');
    }
};
