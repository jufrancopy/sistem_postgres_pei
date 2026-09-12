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
        // 1. Sesiones / Enlaces emitidos para la Unidad de Regulación Farmacéutica
        if (!Schema::hasTable('riiss_sesiones_farmaceuticas')) {
            Schema::create('riiss_sesiones_farmaceuticas', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('token', 64)->unique()->index();
                $table->string('codigo_acceso', 30)->index();
                $table->string('analista_nombre', 200);
                $table->string('analista_cargo', 150)->nullable()->default('Analista de la Unidad de Regulación Farmacéutica');
                $table->string('matricula_profesional', 50)->nullable();
                $table->string('analista_documento', 50)->nullable();
                $table->string('analista_telefono', 50)->nullable();
                $table->string('analista_email', 150)->nullable();
                $table->text('notas')->nullable();
                $table->string('estado', 20)->default('activo'); // activo, completado, cancelado
                $table->unsignedBigInteger('created_by_user_id')->nullable();
                $table->timestamps();
            });
        }

        // 2. Registros de Validación / Dictamen Farmacéutico por Especialidad
        if (!Schema::hasTable('riiss_validacion_farmaceutica_especialidades')) {
            Schema::create('riiss_validacion_farmaceutica_especialidades', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('sesion_farmaceutica_id')->index();
                $table->unsignedBigInteger('especialidad_id')->index();
                $table->string('estado', 30)->default('pendiente'); // pendiente, validada, observada
                $table->text('observaciones_tecnicas')->nullable();
                $table->longText('firma_digital')->nullable();
                $table->string('firmado_por', 200)->nullable();
                $table->string('firmado_documento', 50)->nullable();
                $table->string('firmado_matricula', 50)->nullable();
                $table->timestamp('firmado_at')->nullable();
                $table->timestamps();

                $table->foreign('sesion_farmaceutica_id')
                    ->references('id')
                    ->on('riiss_sesiones_farmaceuticas')
                    ->onDelete('cascade');
            });
        }

        // 3. Dictamen Individual por Medicamento asignado a la Especialidad
        if (!Schema::hasTable('riiss_validacion_farmaceutica_medicamentos')) {
            Schema::create('riiss_validacion_farmaceutica_medicamentos', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('validacion_farmaceutica_especialidad_id')->nullable()->index();
                $table->unsignedBigInteger('sesion_farmaceutica_id')->nullable()->index();
                $table->unsignedBigInteger('especialidad_id')->index();
                $table->unsignedBigInteger('medicamento_id')->index();
                $table->string('estado_validacion', 30); // validado (aprobado), invalidado (no pertinente), incorporado (nuevo agregado)
                $table->text('justificacion')->nullable();
                $table->string('validado_por', 200)->nullable();
                $table->timestamp('validado_at')->nullable();
                $table->timestamps();

                $table->foreign('sesion_farmaceutica_id')
                    ->references('id')
                    ->on('riiss_sesiones_farmaceuticas')
                    ->onDelete('set null');

                $table->foreign('medicamento_id')
                    ->references('id')
                    ->on('riiss_medicamentos')
                    ->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riiss_validacion_farmaceutica_medicamentos');
        Schema::dropIfExists('riiss_validacion_farmaceutica_especialidades');
        Schema::dropIfExists('riiss_sesiones_farmaceuticas');
    }
};
