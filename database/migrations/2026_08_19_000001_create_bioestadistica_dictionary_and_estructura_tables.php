<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('pgsql')->create('bioestadistica.variables', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 20);
            $table->string('nombre', 200);
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['codigo', 'nombre']);
            $table->index('codigo');
        });

        Schema::connection('pgsql')->create('bioestadistica.variable_detalles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('variable_id')->constrained('bioestadistica.variables')->restrictOnDelete();
            $table->string('nombre', 250);
            $table->integer('orden')->default(0);
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['variable_id', 'nombre']);
            $table->index('variable_id');
        });

        Schema::connection('pgsql')->create('bioestadistica.prestaciones', function (Blueprint $table) {
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

        Schema::connection('pgsql')->create('bioestadistica.estructura_departamentos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 200)->unique();
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::connection('pgsql')->create('bioestadistica.estructura_servicios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('departamento_id')->constrained('bioestadistica.estructura_departamentos')->restrictOnDelete();
            $table->string('nombre', 200);
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['departamento_id', 'nombre']);
            $table->index('departamento_id');
        });

        Schema::connection('pgsql')->table('bioestadistica.establecimientos', function (Blueprint $table) {
            $table->foreignId('estructura_departamento_id')
                ->nullable()
                ->constrained('bioestadistica.estructura_departamentos')
                ->nullOnDelete();
            $table->foreignId('estructura_servicio_id')
                ->nullable()
                ->constrained('bioestadistica.estructura_servicios')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::connection('pgsql')->table('bioestadistica.establecimientos', function (Blueprint $table) {
            $table->dropConstrainedForeignId('estructura_servicio_id');
            $table->dropConstrainedForeignId('estructura_departamento_id');
        });
        Schema::connection('pgsql')->dropIfExists('bioestadistica.estructura_servicios');
        Schema::connection('pgsql')->dropIfExists('bioestadistica.estructura_departamentos');
        Schema::connection('pgsql')->dropIfExists('bioestadistica.prestaciones');
        Schema::connection('pgsql')->dropIfExists('bioestadistica.variable_detalles');
        Schema::connection('pgsql')->dropIfExists('bioestadistica.variables');
    }
};
