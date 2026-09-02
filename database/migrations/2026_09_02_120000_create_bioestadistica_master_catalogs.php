<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $catalog = function (Blueprint $table, bool $withNombreUnique = true): void {
            $table->id();
            $table->string('codigo', 80)->nullable();
            $table->string('nombre', 400);
            $table->string('nombre_normalizado', 400)->nullable();
            $table->text('descripcion')->nullable();
            $table->integer('orden')->default(0);
            $table->boolean('activo')->default(true);
            $table->jsonb('meta')->nullable();
            $table->timestamps();
            $table->softDeletes();
            if ($withNombreUnique) {
                $table->unique('nombre');
            }
            $table->index('activo');
        };

        Schema::connection('pgsql')->create('bioestadistica.especialidades_medicas', function (Blueprint $table) use ($catalog) {
            $catalog($table, false);
            $table->string('contexto', 40);
            $table->string('especialidad_base', 200)->nullable();
            $table->foreignId('especialidad_base_id')
                ->nullable()
                ->constrained('bioestadistica.especialidades_medicas')
                ->nullOnDelete();
            $table->unique(['nombre', 'contexto']);
            $table->index('contexto');
        });

        Schema::connection('pgsql')->create('bioestadistica.determinaciones_estudios', function (Blueprint $table) use ($catalog) {
            $catalog($table);
            $table->string('familia', 30);
            $table->string('modalidad', 120)->nullable();
            $table->string('unidad_medida', 40)->nullable();
            $table->boolean('es_agregado')->default(false);
            $table->index('familia');
        });

        Schema::connection('pgsql')->create('bioestadistica.procedimientos_catalogo', function (Blueprint $table) use ($catalog) {
            $catalog($table);
            $table->string('categoria', 40);
            $table->boolean('requiere_pacientes')->default(false);
            $table->boolean('requiere_prestaciones')->default(false);
            $table->index('categoria');
        });

        Schema::connection('pgsql')->create('bioestadistica.vacunas', function (Blueprint $table) use ($catalog) {
            $catalog($table);
            $table->string('abreviatura', 20)->nullable();
            $table->string('grupo_programa', 60)->nullable();
            $table->boolean('requiere_lote')->default(false);
        });

        Schema::connection('pgsql')->create('bioestadistica.prestaciones_catalogo', function (Blueprint $table) use ($catalog) {
            $catalog($table);
            $table->string('familia', 40);
            $table->string('dominio_codigo', 10)->nullable();
            $table->string('tipo_valor', 20)->nullable();
            $table->boolean('es_indicador')->default(false);
            $table->string('unidad', 40)->nullable();
            $table->index('familia');
        });

        Schema::connection('pgsql')->table('bioestadistica.variable_detalles', function (Blueprint $table) {
            $table->string('catalogo_tipo', 30)->nullable()->after('nombre');
            $table->string('layout_captura', 20)->nullable()->after('catalogo_tipo');
            $table->jsonb('meta')->nullable()->after('layout_captura');
        });

        Schema::connection('pgsql')->create('bioestadistica.detalle_catalogo_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('variable_detalle_id')
                ->constrained('bioestadistica.variable_detalles')
                ->cascadeOnDelete();
            $table->string('catalogo_tipo', 30);
            $table->unsignedBigInteger('catalogo_item_id');
            $table->integer('orden')->default(0);
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['variable_detalle_id', 'catalogo_tipo', 'catalogo_item_id'], 'detalle_catalogo_unique');
            $table->index(['catalogo_tipo', 'catalogo_item_id']);
        });

        Schema::connection('pgsql')->create('bioestadistica.prestacion_catalog_map', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prestacion_id')
                ->constrained('bioestadistica.prestaciones')
                ->cascadeOnDelete();
            $table->string('catalogo_tipo', 30);
            $table->unsignedBigInteger('catalogo_item_id');
            $table->timestamps();
            $table->unique('prestacion_id');
        });
    }

    public function down(): void
    {
        Schema::connection('pgsql')->dropIfExists('bioestadistica.prestacion_catalog_map');
        Schema::connection('pgsql')->dropIfExists('bioestadistica.detalle_catalogo_items');
        Schema::connection('pgsql')->table('bioestadistica.variable_detalles', function (Blueprint $table) {
            $table->dropColumn(['catalogo_tipo', 'layout_captura', 'meta']);
        });
        Schema::connection('pgsql')->dropIfExists('bioestadistica.prestaciones_catalogo');
        Schema::connection('pgsql')->dropIfExists('bioestadistica.vacunas');
        Schema::connection('pgsql')->dropIfExists('bioestadistica.procedimientos_catalogo');
        Schema::connection('pgsql')->dropIfExists('bioestadistica.determinaciones_estudios');
        Schema::connection('pgsql')->dropIfExists('bioestadistica.especialidades_medicas');
    }
};
