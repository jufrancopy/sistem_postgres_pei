<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('pgsql')->create('bioestadistica.hosp_episodios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('establecimiento_id')
                ->constrained('bioestadistica.establecimientos')
                ->restrictOnDelete();
            $table->foreignId('record_id')
                ->nullable()
                ->constrained('bioestadistica.records')
                ->nullOnDelete();
            $table->smallInteger('periodo_anio');
            $table->smallInteger('periodo_mes');
            $table->text('cedula')->nullable();
            $table->string('cedula_hash', 64)->nullable();
            $table->string('sexo', 1)->nullable();
            $table->string('seguro', 80)->nullable();
            $table->integer('edad')->nullable();
            $table->date('fecha_ingreso');
            $table->date('fecha_egreso')->nullable();
            $table->string('servicio', 150)->nullable();
            $table->string('diagnostico', 400)->nullable();
            $table->string('cie10', 10)->nullable();
            $table->string('tipo_alta', 50)->nullable();
            $table->boolean('cirugia')->default(false);
            $table->string('tipo_cirugia', 150)->nullable();
            $table->boolean('recien_nacido')->default(false);
            $table->boolean('cesarea')->default(false);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('source_import_job_id')->nullable();
            $table->unsignedInteger('source_row')->nullable();
            $table->string('source_fingerprint', 64)->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['establecimiento_id', 'periodo_anio', 'periodo_mes'], 'hosp_episodios_establecimiento_idx');
            $table->index(['establecimiento_id', 'fecha_egreso'], 'hosp_episodios_egreso_est_idx');
            $table->index('fecha_egreso', 'hosp_episodios_egreso_idx');
            $table->index('fecha_ingreso', 'hosp_episodios_ingreso_idx');
            $table->index('cie10', 'hosp_episodios_cie10_idx');
            $table->index('tipo_alta', 'hosp_episodios_alta_idx');
            $table->index('cedula_hash', 'hosp_episodios_cedula_hash_idx');
        });

        DB::connection('pgsql')->statement(
            "ALTER TABLE bioestadistica.hosp_episodios
             ADD CONSTRAINT hosp_episodios_sexo_chk CHECK (sexo IN ('M','F') OR sexo IS NULL)"
        );
        DB::connection('pgsql')->statement(
            'ALTER TABLE bioestadistica.hosp_episodios
             ADD CONSTRAINT hosp_episodios_mes_chk CHECK (periodo_mes BETWEEN 1 AND 12)'
        );
        DB::connection('pgsql')->statement(
            'ALTER TABLE bioestadistica.hosp_episodios
             ADD CONSTRAINT hosp_episodios_anio_chk CHECK (periodo_anio BETWEEN 1990 AND 2100)'
        );
        DB::connection('pgsql')->statement(
            'ALTER TABLE bioestadistica.hosp_episodios
             ADD CONSTRAINT hosp_episodios_fechas_chk CHECK (fecha_egreso IS NULL OR fecha_egreso >= fecha_ingreso)'
        );
        DB::connection('pgsql')->statement(
            'ALTER TABLE bioestadistica.hosp_episodios
             ADD CONSTRAINT hosp_episodios_edad_chk CHECK (edad IS NULL OR edad BETWEEN 0 AND 130)'
        );
        DB::connection('pgsql')->statement(
            'CREATE UNIQUE INDEX hosp_episodios_fingerprint_unique
             ON bioestadistica.hosp_episodios (source_fingerprint)
             WHERE source_fingerprint IS NOT NULL AND deleted_at IS NULL'
        );
        DB::connection('pgsql')->statement(
            "COMMENT ON COLUMN bioestadistica.hosp_episodios.cedula IS 'Cédula cifrada en aplicación. Buscar por cedula_hash.'"
        );
        DB::connection('pgsql')->statement(
            "COMMENT ON COLUMN bioestadistica.hosp_episodios.record_id IS 'Registro agregado SP10 al que este episodio contribuye.'"
        );
    }

    public function down(): void
    {
        Schema::connection('pgsql')->dropIfExists('bioestadistica.hosp_episodios');
    }
};
