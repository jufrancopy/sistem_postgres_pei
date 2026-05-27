<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluaciones', function (Blueprint $table) {
            $table->id();
            $table->string('id_establecimiento');
            $table->foreign('id_establecimiento')
                  ->references('id_establecimiento')
                  ->on('establecimientos');
            $table->date('fecha_evaluacion');
            $table->string('evaluador_nombre')->nullable();
            $table->string('evaluador_telefono')->nullable();
            $table->string('evaluador_usuario_institucional')->nullable();
            $table->string('estado', 20)->default('borrador')
                  ->comment('borrador, en_progreso, completada, verificada, rechazada');
            $table->decimal('porcentaje_cumplimiento', 5, 2)->nullable();
            $table->string('clasificacion_resultado', 30)->nullable()
                  ->comment('CUMPLE, CUMPLE_PARCIALMENTE, NO_CUMPLE');
            $table->text('observaciones_generales')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('estado');
            $table->index('fecha_evaluacion');
            $table->index('clasificacion_resultado');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluaciones');
    }
};
