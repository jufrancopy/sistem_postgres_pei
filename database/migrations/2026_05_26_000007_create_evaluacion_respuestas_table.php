<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluacion_respuestas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluacion_id')->constrained('evaluaciones')->cascadeOnDelete();
            $table->foreignId('formulario_pregunta_id')->constrained('formulario_preguntas')->cascadeOnDelete();
            $table->text('respuesta');
            $table->string('estado_cumplimiento', 20)->default('pendiente')
                  ->comment('cumple, no_cumple, no_aplica, pendiente');
            $table->text('observacion')->nullable();
            $table->json('evidencia_adjuntos')->nullable();
            $table->timestamps();

            $table->unique(
                ['evaluacion_id', 'formulario_pregunta_id'],
                'resp_unica'
            );
            $table->index('estado_cumplimiento');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluacion_respuestas');
    }
};
