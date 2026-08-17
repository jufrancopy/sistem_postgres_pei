<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('pgsql')->create('bioestadistica.import_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('tipo', 40);
            $table->string('estado', 30)->default('subido');
            $table->string('archivo_original', 255);
            $table->string('archivo_path', 500);
            $table->string('checksum', 64);
            $table->jsonb('analisis')->nullable();
            $table->jsonb('mapeo')->nullable();
            $table->jsonb('resumen')->nullable();
            $table->text('error')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('confirmed_by')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();
        });
        DB::connection('pgsql')->statement(
            "ALTER TABLE bioestadistica.import_jobs
             ADD CONSTRAINT bio_import_jobs_tipo_check
             CHECK (tipo IN ('generico', 'variables_salud', 'establecimientos_dim', 'formularios_sp'))"
        );
        DB::connection('pgsql')->statement(
            "ALTER TABLE bioestadistica.import_jobs
             ADD CONSTRAINT bio_import_jobs_estado_check
             CHECK (estado IN ('subido', 'analizado', 'mapeado', 'confirmado', 'completado', 'error'))"
        );
        DB::connection('pgsql')->statement(
            'CREATE INDEX bio_import_jobs_created_by_index
             ON bioestadistica.import_jobs (created_by, created_at DESC)'
        );
    }

    public function down(): void
    {
        Schema::connection('pgsql')->dropIfExists('bioestadistica.import_jobs');
    }
};
