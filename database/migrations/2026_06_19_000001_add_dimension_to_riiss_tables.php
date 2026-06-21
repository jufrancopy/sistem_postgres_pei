<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. formulario_preguntas: agregar dimension
        Schema::table('formulario_preguntas', function (Blueprint $table) {
            $table->string('dimension', 30)->default('sin_clasificar')->after('tags_cartera');
        });

        // 2. gap_analysis_items: agregar dimension
        Schema::table('gap_analysis_items', function (Blueprint $table) {
            $table->string('dimension', 30)->default('sin_clasificar')->after('grupo_servicio');
        });

        // 3. evaluaciones: agregar columnas para segunda dimensión
        Schema::table('evaluaciones', function (Blueprint $table) {
            $table->decimal('pct_habilitacion', 5, 2)->nullable()->after('porcentaje_cumplimiento');
            $table->string('clasificacion_habilitacion', 30)->nullable()->after('clasificacion_resultado');
        });

        // 4. Clasificar automáticamente las preguntas que ya tienen servicio_cartera_grupo
        DB::statement("
            UPDATE formulario_preguntas
            SET dimension = 'cartera_servicios'
            WHERE servicio_cartera_grupo IS NOT NULL AND servicio_cartera_grupo != ''
        ");

        // 5. Secciones que son puro metadata (no se evalúan)
        DB::statement("
            UPDATE formulario_preguntas
            SET dimension = 'metadata'
            WHERE formulario_seccion_id IN (
                SELECT id FROM formulario_secciones
                WHERE seccion IN (
                    'Introducción',
                    'Datos de Identificación',
                    'Datos del encargado de llenado del formulario'
                )
            )
        ");

        // 6. Secciones que son condiciones habilitantes claras
        DB::statement("
            UPDATE formulario_preguntas
            SET dimension = 'condiciones_habilitantes'
            WHERE dimension = 'sin_clasificar'
            AND formulario_seccion_id IN (
                SELECT id FROM formulario_secciones
                WHERE seccion IN (
                    'Requerimientos documentales',
                    'Área administrativa',
                    'Datos generales del establecimiento'
                )
            )
        ");

        // 7. gap_analysis_items existentes: heredar dimension según si tienen cartera_servicio_id
        DB::statement("
            UPDATE gap_analysis_items
            SET dimension = 'cartera_servicios'
            WHERE cartera_servicio_id IS NOT NULL
        ");
    }

    public function down(): void
    {
        Schema::table('formulario_preguntas', function (Blueprint $table) {
            $table->dropColumn('dimension');
        });

        Schema::table('gap_analysis_items', function (Blueprint $table) {
            $table->dropColumn('dimension');
        });

        Schema::table('evaluaciones', function (Blueprint $table) {
            $table->dropColumn(['pct_habilitacion', 'clasificacion_habilitacion']);
        });
    }
};
