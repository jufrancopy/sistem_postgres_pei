<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('riiss_asignaciones', function (Blueprint $table) {
            $table->id();
            $table->string('id_establecimiento');
            $table->foreign('id_establecimiento')
                  ->references('id_establecimiento')
                  ->on('establecimientos');
            $table->foreignId('evaluacion_id')->nullable()->constrained('evaluaciones')->nullOnDelete();
            $table->foreignId('asignado_por')->constrained('users')->cascadeOnDelete();
            $table->foreignId('evaluador_id')->constrained('users')->cascadeOnDelete();
            $table->date('fecha_limite')->nullable();
            $table->string('estado', 20)->default('pendiente')
                  ->comment('pendiente, en_progreso, completada, vencida, cancelada');
            $table->text('instrucciones')->nullable();
            $table->timestamp('notificado_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['evaluador_id', 'estado']);
            $table->index(['id_establecimiento', 'estado']);
            $table->index('estado');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riiss_asignaciones');
    }
};
