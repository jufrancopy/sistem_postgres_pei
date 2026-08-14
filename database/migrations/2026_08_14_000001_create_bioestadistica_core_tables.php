<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::connection('pgsql')->statement('CREATE SCHEMA IF NOT EXISTS bioestadistica');

        Schema::connection('pgsql')->create('bioestadistica.departamentos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 10)->unique();
            $table->string('nombre', 150)->unique();
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::connection('pgsql')->create('bioestadistica.distritos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('departamento_id')->constrained('bioestadistica.departamentos')->restrictOnDelete();
            $table->string('codigo', 20)->nullable();
            $table->string('nombre', 150);
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['departamento_id', 'nombre']);
            $table->index('departamento_id');
        });

        Schema::connection('pgsql')->create('bioestadistica.microredes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 150)->unique();
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::connection('pgsql')->create('bioestadistica.tipos_establecimiento', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 150)->unique();
            $table->text('descripcion')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::connection('pgsql')->create('bioestadistica.grados_complejidad', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 10);
            $table->string('descripcion', 200);
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['codigo', 'descripcion'], 'bio_grados_codigo_descripcion_unique');
        });

        Schema::connection('pgsql')->create('bioestadistica.areas_gestion', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 150)->unique();
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::connection('pgsql')->create('bioestadistica.establecimientos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 30)->unique();
            $table->string('nombre', 250);
            $table->foreignId('distrito_id')->nullable()->constrained('bioestadistica.distritos')->restrictOnDelete();
            $table->foreignId('microred_id')->nullable()->constrained('bioestadistica.microredes')->nullOnDelete();
            $table->foreignId('tipo_establecimiento_id')->nullable()->constrained('bioestadistica.tipos_establecimiento')->nullOnDelete();
            $table->foreignId('grado_complejidad_id')->nullable()->constrained('bioestadistica.grados_complejidad')->nullOnDelete();
            $table->foreignId('area_gestion_id')->nullable()->constrained('bioestadistica.areas_gestion')->nullOnDelete();
            $table->string('nivel_atencion', 50)->nullable();
            $table->string('prestador', 80)->nullable();
            $table->string('situacion_inmueble', 120)->nullable();
            $table->string('sistema', 30)->nullable();
            $table->string('codigo_sih', 30)->nullable();
            $table->decimal('latitud', 10, 7)->nullable();
            $table->decimal('longitud', 10, 7)->nullable();
            $table->text('observacion')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index('distrito_id');
            $table->index('microred_id');
            $table->index('codigo_sih');
        });
    }

    public function down(): void
    {
        Schema::connection('pgsql')->dropIfExists('bioestadistica.establecimientos');
        Schema::connection('pgsql')->dropIfExists('bioestadistica.areas_gestion');
        Schema::connection('pgsql')->dropIfExists('bioestadistica.grados_complejidad');
        Schema::connection('pgsql')->dropIfExists('bioestadistica.tipos_establecimiento');
        Schema::connection('pgsql')->dropIfExists('bioestadistica.microredes');
        Schema::connection('pgsql')->dropIfExists('bioestadistica.distritos');
        Schema::connection('pgsql')->dropIfExists('bioestadistica.departamentos');
        DB::connection('pgsql')->statement('DROP SCHEMA IF EXISTS bioestadistica');
    }
};
