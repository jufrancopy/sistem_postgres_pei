<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('pgsql')->create('bioestadistica.usuario_captura_asignaciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreignId('formulario_id')
                ->nullable()
                ->constrained('bioestadistica.formularios')
                ->nullOnDelete();
            $table->foreignId('establecimiento_id')
                ->constrained('bioestadistica.establecimientos')
                ->cascadeOnDelete();
            $table->boolean('activo')->default(true);
            $table->unsignedBigInteger('asignado_por')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'activo']);
            $table->index('establecimiento_id');
        });

        DB::connection('pgsql')->statement(
            'CREATE UNIQUE INDEX bio_uca_user_est_all_forms_unique
             ON bioestadistica.usuario_captura_asignaciones (user_id, establecimiento_id)
             WHERE formulario_id IS NULL AND activo = true'
        );

        DB::connection('pgsql')->statement(
            'CREATE UNIQUE INDEX bio_uca_user_form_est_unique
             ON bioestadistica.usuario_captura_asignaciones (user_id, formulario_id, establecimiento_id)
             WHERE formulario_id IS NOT NULL AND activo = true'
        );
    }

    public function down(): void
    {
        Schema::connection('pgsql')->dropIfExists('bioestadistica.usuario_captura_asignaciones');
    }
};
