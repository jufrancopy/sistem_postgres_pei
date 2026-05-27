<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Agrega campos MECIP 2015 al análisis FODA:
 * - causa_raiz: Causa raíz de la debilidad/amenaza (Operativa, Estructural, Tecnológica)
 * - accion_mejora: Plan de acción sugerido para cerrar el ciclo de control interno
 * - control_preventivo: Control definido para mitigar el riesgo identificado
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('planificacion.foda_analisis', function (Blueprint $table) {
            $table->enum('causa_raiz', ['operativa', 'estructural', 'tecnologica', 'normativa', 'otra'])
                ->nullable()
                ->after('iea_clasificacion')
                ->comment('Causa raíz MECIP: tipo de origen del problema');

            $table->text('accion_mejora')
                ->nullable()
                ->after('causa_raiz')
                ->comment('Acción de mejora sugerida — cierra el ciclo de control interno MECIP');

            $table->text('control_preventivo')
                ->nullable()
                ->after('accion_mejora')
                ->comment('Control preventivo definido para mitigar el riesgo');
        });
    }

    public function down(): void
    {
        Schema::table('planificacion.foda_analisis', function (Blueprint $table) {
            $table->dropColumn(['causa_raiz', 'accion_mejora', 'control_preventivo']);
        });
    }
};
