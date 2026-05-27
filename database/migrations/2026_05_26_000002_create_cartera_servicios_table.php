<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cartera_servicios', function (Blueprint $table) {
            $table->id();
            $table->string('paciente_objetivo')->nullable();
            $table->tinyInteger('nivel_atencion')->comment('1, 2, 3');
            $table->tinyInteger('grado_complejidad')->comment('1, 2, 3');
            $table->string('tipo_establecimiento', 40)->comment('Extra muro, Intra muro, etc.');
            $table->string('tipo_prestacion', 60)->comment('Promoción y Prevención, Consulta médica, etc.');
            $table->text('objetivos_generales')->nullable();
            $table->string('variable_prestacion')->nullable();
            $table->string('servicio', 120)->comment('Nombre del servicio');
            $table->string('detalles')->nullable();
            $table->string('detalles_2')->nullable();
            $table->text('descripcion')->nullable();
            $table->string('especialidad_1', 50)->nullable();
            $table->string('especialidad_2', 50)->nullable();
            $table->string('patologias_por_sistema')->nullable();
            $table->string('patologias')->nullable();
            $table->string('encargado_profesional')->nullable();
            $table->boolean('requerido')->default(false);
            $table->text('observacion')->nullable();

            // Campos para matching
            $table->string('grupo_servicio', 80)->nullable();
            $table->string('codigo_formulario_relacionado')->nullable();
            $table->boolean('aplica_puesto_sanitario')->default(true);
            $table->boolean('aplica_unidad_sanitaria')->default(true);
            $table->boolean('aplica_clinica_periferica')->default(true);
            $table->boolean('aplica_hospital_baja')->default(true);
            $table->boolean('aplica_hospital_mediana')->default(true);
            $table->boolean('aplica_hospital_alta')->default(true);

            $table->timestamps();

            $table->index(['nivel_atencion', 'grado_complejidad']);
            $table->index('tipo_prestacion');
            $table->index('servicio');
            $table->index('grupo_servicio');
            $table->index('especialidad_1');
            $table->index('requerido');
            $table->index(['nivel_atencion', 'grado_complejidad', 'requerido']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cartera_servicios');
    }
};
