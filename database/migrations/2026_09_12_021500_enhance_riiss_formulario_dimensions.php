<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('formulario_secciones', function (Blueprint $table) {
            if (!Schema::hasColumn('formulario_secciones', 'dimension')) {
                $table->string('dimension', 50)->default('cartera_servicios')->after('sub_seccion');
            }
            if (!Schema::hasColumn('formulario_secciones', 'icono')) {
                $table->string('icono', 50)->nullable()->after('dimension');
            }
            if (!Schema::hasColumn('formulario_secciones', 'descripcion')) {
                $table->text('descripcion')->nullable()->after('icono');
            }
            if (!Schema::hasColumn('formulario_secciones', 'codigo_seccion')) {
                $table->string('codigo_seccion', 30)->nullable()->after('descripcion');
            }
        });

        Schema::table('formulario_preguntas', function (Blueprint $table) {
            if (!Schema::hasColumn('formulario_preguntas', 'dimension')) {
                $table->string('dimension', 50)->default('cartera_servicios')->after('formulario_seccion_id');
            }
            if (!Schema::hasColumn('formulario_preguntas', 'grado_complejidad_min')) {
                $table->smallInteger('grado_complejidad_min')->nullable()->default(1)->after('orden');
            }
            if (!Schema::hasColumn('formulario_preguntas', 'es_requerido')) {
                $table->boolean('es_requerido')->default(true)->after('grado_complejidad_min');
            }
            if (!Schema::hasColumn('formulario_preguntas', 'peso_ponderacion')) {
                $table->decimal('peso_ponderacion', 5, 2)->default(1.00)->after('es_requerido');
            }
            if (!Schema::hasColumn('formulario_preguntas', 'metadata_cartera')) {
                $table->json('metadata_cartera')->nullable()->after('especialidad_relacionada');
            }
            if (!Schema::hasColumn('formulario_preguntas', 'codigo_pregunta')) {
                $table->string('codigo_pregunta', 50)->nullable()->after('metadata_cartera');
            }
        });
    }

    public function down(): void
    {
        Schema::table('formulario_secciones', function (Blueprint $table) {
            $table->dropColumn(['dimension', 'icono', 'descripcion', 'codigo_seccion']);
        });

        Schema::table('formulario_preguntas', function (Blueprint $table) {
            $table->dropColumn(['dimension', 'grado_complejidad_min', 'es_requerido', 'peso_ponderacion', 'metadata_cartera', 'codigo_pregunta']);
        });
    }
};
