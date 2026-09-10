<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('riiss_validacion_especialidades', function (Blueprint $table) {
            $table->id();
            $table->string('establecimiento_id');
            $table->string('token', 64)->unique();
            $table->string('estado', 30)->default('pendiente')->comment('pendiente, en_progreso, completada, firmada');
            
            // Datos del Validador de Área Interior
            $table->string('validador_nombre', 200)->nullable();
            $table->string('validador_cargo', 150)->nullable();
            $table->string('validador_documento', 50)->nullable();
            $table->string('validador_telefono', 50)->nullable();
            $table->string('validador_email', 150)->nullable();
            $table->longText('validador_firma')->nullable();
            $table->timestamp('validador_firmado_at')->nullable();
            $table->text('observaciones_cierre')->nullable();
            
            // Contadores de control
            $table->integer('total_especialidades')->default(0);
            $table->integer('total_validadas')->default(0);
            $table->integer('total_inactivadas')->default(0);
            $table->integer('total_pendientes')->default(0);

            $table->unsignedBigInteger('created_by_user_id')->nullable();
            $table->timestamps();

            $table->foreign('establecimiento_id')->references('id_establecimiento')->on('establecimientos')->onDelete('cascade');
            $table->foreign('created_by_user_id')->references('id')->on('users')->nullOnDelete();
        });

        Schema::create('riiss_validacion_especialidad_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('validacion_id')->constrained('riiss_validacion_especialidades')->onDelete('cascade');
            $table->unsignedBigInteger('especialidad_id');
            $table->string('estado', 30)->default('pendiente')->comment('pendiente, validada, inactiva');
            $table->text('justificacion_inactivacion')->nullable();
            $table->boolean('es_agregada_en_terreno')->default(false);
            $table->timestamp('validado_at')->nullable();
            $table->string('validado_por', 200)->nullable();
            $table->timestamps();

            $table->foreign('especialidad_id')->references('id')->on('bioestadistica.especialidades_medicas')->onDelete('cascade');
            $table->unique(['validacion_id', 'especialidad_id'], 'val_esp_item_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riiss_validacion_especialidad_items');
        Schema::dropIfExists('riiss_validacion_especialidades');
    }
};
