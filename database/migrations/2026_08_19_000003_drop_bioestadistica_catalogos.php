<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $db = DB::connection('pgsql');

        $db->statement("
            DELETE FROM bioestadistica.record_values rv
            USING bioestadistica.fields f
            WHERE rv.field_id = f.id
              AND (
                f.catalogo_id IS NOT NULL
                OR f.type IN ('tabla', 'select', 'radio', 'multiselect')
                OR COALESCE(f.config->>'row_source', '') = 'catalogo'
              )
        ");

        Schema::connection('pgsql')->table('bioestadistica.fields', function (Blueprint $table) {
            $table->dropConstrainedForeignId('catalogo_id');
            $table->dropConstrainedForeignId('variable_definition_id');
        });

        Schema::connection('pgsql')->dropIfExists('bioestadistica.variable_definitions');
        Schema::connection('pgsql')->dropIfExists('bioestadistica.catalog_items');
        Schema::connection('pgsql')->dropIfExists('bioestadistica.catalogos');
    }

    public function down(): void
    {
        Schema::connection('pgsql')->create('bioestadistica.catalogos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 80)->unique();
            $table->string('nombre', 200);
            $table->text('descripcion')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::connection('pgsql')->create('bioestadistica.catalog_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('catalogo_id')->constrained('bioestadistica.catalogos')->cascadeOnDelete();
            $table->string('codigo', 80);
            $table->string('label', 400);
            $table->integer('orden')->default(0);
            $table->boolean('activo')->default(true);
            $table->string('domain_code', 20)->nullable();
            $table->string('tipo_registro', 200)->nullable();
            $table->string('prestacion', 400)->nullable();
            $table->jsonb('meta')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['catalogo_id', 'codigo']);
        });

        Schema::connection('pgsql')->table('bioestadistica.fields', function (Blueprint $table) {
            $table->foreignId('catalogo_id')->nullable()->constrained('bioestadistica.catalogos')->nullOnDelete();
        });
    }
};
