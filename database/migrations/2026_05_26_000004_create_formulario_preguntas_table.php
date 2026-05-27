<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('formulario_preguntas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('formulario_seccion_id')->constrained('formulario_secciones')->cascadeOnDelete();
            $table->text('pregunta');
            $table->string('tipo_respuesta', 20)->default('texto')
                  ->comment('texto, si_no, si_no_na, opcion_multiple, numero, lista, checklist');
            $table->json('opciones')->nullable();
            $table->string('respuesta_ejemplo')->nullable();
            $table->integer('orden')->default(0);
            $table->boolean('activa')->default(true);

            // Tags para matching con cartera
            $table->json('tags_cartera')->nullable();
            $table->string('servicio_cartera_grupo')->nullable();
            $table->string('especialidad_relacionada')->nullable();

            $table->timestamps();

            $table->index('tipo_respuesta');
            $table->index('servicio_cartera_grupo');
            $table->index('especialidad_relacionada');
            $table->index(['formulario_seccion_id', 'orden']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('formulario_preguntas');
    }
};
