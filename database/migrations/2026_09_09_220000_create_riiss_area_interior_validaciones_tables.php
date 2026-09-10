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
        Schema::create('riiss_sesiones_validador', function (Blueprint $table) {
            $table->id();
            $table->string('token', 64)->unique()->index();
            $table->string('codigo_acceso', 20)->unique()->index();
            $table->string('analista_nombre', 200);
            $table->string('analista_cargo', 150)->nullable();
            $table->string('analista_documento', 50)->nullable();
            $table->string('analista_telefono', 50)->nullable();
            $table->string('analista_email', 150)->nullable();
            $table->string('departamento_filtro', 100)->nullable()->comment('Null = Todos los departamentos del Área Interior');
            $table->string('estado', 30)->default('activo')->comment('activo, finalizado');
            $table->text('notas')->nullable();
            $table->longText('firma_digital')->nullable();
            $table->timestamp('firmado_at')->nullable();
            $table->foreignId('created_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('riiss_validacion_especialidad_registros', function (Blueprint $table) {
            $table->id();
            $table->string('establecimiento_id', 50);
            $table->unsignedBigInteger('especialidad_id');
            $table->string('estado', 30)->default('activa')->comment('activa, inactiva');
            $table->text('justificacion')->nullable()->comment('Opcional: motivo u observación');
            $table->boolean('es_agregada')->default(false);
            $table->foreignId('sesion_validador_id')->nullable()->constrained('riiss_sesiones_validador')->nullOnDelete();
            $table->string('validado_por', 200)->nullable();
            $table->timestamp('validado_at')->nullable();
            $table->timestamps();

            $table->foreign('establecimiento_id')->references('id_establecimiento')->on('establecimientos')->onDelete('cascade');
            $table->foreign('especialidad_id')->references('id')->on('bioestadistica.especialidades_medicas')->onDelete('cascade');
            $table->unique(['establecimiento_id', 'especialidad_id'], 'riiss_val_esp_reg_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riiss_validacion_especialidad_registros');
        Schema::dropIfExists('riiss_sesiones_validador');
    }
};
