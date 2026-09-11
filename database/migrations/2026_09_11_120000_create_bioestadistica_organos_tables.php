<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Organigrama institucional IPS (fase 1: desde Gerencia de Salud).
 * Establecimientos quedan fuera del árbol; se enlazan luego vía establecimiento_organos.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('pgsql')->create('bioestadistica.organo_tipos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 40)->unique();
            $table->string('nombre', 120);
            $table->unsignedSmallInteger('orden')->default(0);
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::connection('pgsql')->create('bioestadistica.organos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tipo_id')
                ->constrained('bioestadistica.organo_tipos')
                ->restrictOnDelete();
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('bioestadistica.organos')
                ->restrictOnDelete();
            $table->string('nombre', 300);
            $table->string('codigo', 40)->nullable();
            $table->unsignedInteger('orden')->default(0);
            $table->boolean('es_jerarquico')->default(true);
            $table->boolean('activo')->default(true);
            $table->unsignedSmallInteger('fuente_pagina')->nullable();
            $table->text('notas')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['parent_id', 'orden']);
            $table->index(['tipo_id', 'activo']);
            $table->index('nombre');
        });

        // Unicidad lógica: mismo nombre bajo el mismo padre (raíz = parent null).
        // En PostgreSQL los NULL no colisionan en UNIQUE compuesto; la raíz se controla en app/seeder.
        Schema::connection('pgsql')->table('bioestadistica.organos', function (Blueprint $table) {
            $table->unique(['parent_id', 'nombre']);
        });

        Schema::connection('pgsql')->create('bioestadistica.establecimiento_organos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('establecimiento_id')
                ->constrained('bioestadistica.establecimientos')
                ->restrictOnDelete();
            $table->foreignId('organo_id')
                ->constrained('bioestadistica.organos')
                ->restrictOnDelete();
            $table->date('vigente_desde')->nullable();
            $table->date('vigente_hasta')->nullable();
            $table->boolean('es_principal')->default(true);
            $table->text('notas')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['establecimiento_id', 'organo_id']);
            $table->index(['organo_id', 'es_principal']);
        });
    }

    public function down(): void
    {
        Schema::connection('pgsql')->dropIfExists('bioestadistica.establecimiento_organos');
        Schema::connection('pgsql')->dropIfExists('bioestadistica.organos');
        Schema::connection('pgsql')->dropIfExists('bioestadistica.organo_tipos');
    }
};
