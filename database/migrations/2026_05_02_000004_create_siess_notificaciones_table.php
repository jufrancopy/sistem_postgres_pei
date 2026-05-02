<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('pgsql')->create('estadistica.siess_notificaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('extracto_id')->constrained('estadistica.siess_extractos')->cascadeOnDelete();

            // Destinatario
            $table->unsignedInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            $table->unsignedInteger('direccion_id')->nullable();
            $table->foreign('direccion_id')->references('id')->on('organigramas')->nullOnDelete();

            $table->enum('tipo', [
                'envio_validacion',      // Planificación envía a dirección
                'aprobado',              // Dirección aprueba
                'objetado',              // Dirección objeta
                'aprobado_silencio',     // Sistema aprueba por silencio
                'vencimiento_proximo',   // Alerta: quedan 1-2 días
                'fuente_unica',          // Marcado como fuente única
            ]);

            $table->string('titulo');
            $table->text('mensaje');
            $table->boolean('leida')->default(false);
            $table->timestamp('leida_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'leida']);
            $table->index(['extracto_id']);
        });
    }

    public function down(): void
    {
        Schema::connection('pgsql')->dropIfExists('estadistica.siess_notificaciones');
    }
};
