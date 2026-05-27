<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gap_analysis_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluacion_id')->constrained('evaluaciones')->cascadeOnDelete();
            $table->foreignId('cartera_servicio_id')->nullable()->constrained('cartera_servicios')->nullOnDelete();
            $table->string('servicio_nombre', 120);
            $table->string('grupo_servicio', 80)->nullable();
            $table->string('tipo_prestacion', 60)->nullable();
            $table->string('especialidad', 50)->nullable();
            $table->boolean('requerido_para_nivel')->default(false);
            $table->string('estado', 20)->default('pendiente')
                  ->comment('cumple, no_cumple, no_verificable, no_aplica, pendiente');
            $table->text('criterio_evaluacion')->nullable();
            $table->json('preguntas_relacionadas')->nullable();
            $table->json('respuestas_relacionadas')->nullable();
            $table->text('accion_recomendada')->nullable();
            $table->integer('prioridad')->default(0)->comment('0=normal, 1=alta, 2=crítica');
            $table->timestamps();

            $table->index(['evaluacion_id', 'estado']);
            $table->index(['evaluacion_id', 'requerido_para_nivel']);
            $table->index('prioridad');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gap_analysis_items');
    }
};
