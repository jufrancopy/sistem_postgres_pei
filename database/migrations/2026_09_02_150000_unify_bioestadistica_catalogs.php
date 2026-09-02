<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::connection('pgsql')->table('bioestadistica.record_values')->delete();
        DB::connection('pgsql')->table('bioestadistica.records')->delete();

        Schema::connection('pgsql')->dropIfExists('bioestadistica.prestacion_catalog_map');
        Schema::connection('pgsql')->dropIfExists('bioestadistica.prestaciones');

        DB::connection('pgsql')->statement('ALTER TABLE bioestadistica.procedimientos_catalogo RENAME TO procedimientos');
        DB::connection('pgsql')->statement('ALTER TABLE bioestadistica.prestaciones_catalogo RENAME TO prestaciones');

        $this->createCatalogItemLabelsView();
    }

    public function down(): void
    {
        DB::connection('pgsql')->statement('DROP VIEW IF EXISTS bioestadistica.v_catalog_item_labels');

        DB::connection('pgsql')->statement('ALTER TABLE bioestadistica.prestaciones RENAME TO prestaciones_catalogo');
        DB::connection('pgsql')->statement('ALTER TABLE bioestadistica.procedimientos RENAME TO procedimientos_catalogo');

        Schema::connection('pgsql')->create('bioestadistica.prestaciones', function ($table) {
            $table->id();
            $table->foreignId('detalle_id')->constrained('bioestadistica.variable_detalles')->restrictOnDelete();
            $table->string('nombre', 400);
            $table->integer('orden')->default(0);
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['detalle_id', 'nombre']);
            $table->index('detalle_id');
        });

        Schema::connection('pgsql')->create('bioestadistica.prestacion_catalog_map', function ($table) {
            $table->id();
            $table->foreignId('prestacion_id')->constrained('bioestadistica.prestaciones')->cascadeOnDelete();
            $table->string('catalogo_tipo', 30);
            $table->unsignedBigInteger('catalogo_item_id');
            $table->timestamps();
            $table->unique('prestacion_id');
        });
    }

    private function createCatalogItemLabelsView(): void
    {
        DB::connection('pgsql')->statement(<<<'SQL'
            CREATE OR REPLACE VIEW bioestadistica.v_catalog_item_labels AS
            SELECT 'especialidad_medica'::varchar AS catalogo_tipo, id AS catalogo_item_id, nombre
            FROM bioestadistica.especialidades_medicas
            WHERE deleted_at IS NULL
            UNION ALL
            SELECT 'determinacion', id, nombre
            FROM bioestadistica.determinaciones_estudios
            WHERE deleted_at IS NULL
            UNION ALL
            SELECT 'procedimiento', id, nombre
            FROM bioestadistica.procedimientos
            WHERE deleted_at IS NULL
            UNION ALL
            SELECT 'vacuna', id, nombre
            FROM bioestadistica.vacunas
            WHERE deleted_at IS NULL
            UNION ALL
            SELECT 'prestacion', id, nombre
            FROM bioestadistica.prestaciones
            WHERE deleted_at IS NULL
            SQL);
    }
};
