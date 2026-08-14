<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
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
            $table->string('codigo', 80)->nullable();
            $table->string('label', 400);
            $table->integer('orden')->default(0);
            $table->boolean('activo')->default(true);
            $table->string('domain_code', 10)->nullable();
            $table->string('tipo_registro', 250)->nullable();
            $table->string('prestacion', 400)->nullable();
            $table->jsonb('meta')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['catalogo_id', 'orden']);
            $table->unique(['catalogo_id', 'codigo']);
        });

        Schema::connection('pgsql')->create('bioestadistica.variable_definitions', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_dominio', 10);
            $table->string('dominio', 200);
            $table->string('tipo_registro', 250)->nullable();
            $table->string('prestacion', 400)->nullable();
            $table->foreignId('catalogo_id')->nullable()->constrained('bioestadistica.catalogos')->nullOnDelete();
            $table->jsonb('form_codes')->nullable();
            $table->jsonb('meta')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->index('codigo_dominio');
            $table->unique(
                ['codigo_dominio', 'tipo_registro', 'prestacion'],
                'bio_variable_definitions_content_unique'
            );
        });

        Schema::connection('pgsql')->create('bioestadistica.formularios', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 20)->unique();
            $table->string('nombre', 250);
            $table->text('descripcion')->nullable();
            $table->enum('estado', ['borrador', 'activo', 'archivado'])->default('borrador');
            $table->enum('periodicidad', ['diaria', 'semanal', 'mensual', 'trimestral', 'anual', 'ad_hoc'])->default('mensual');
            $table->enum('layout_type', ['tabular', 'nominativo', 'matriz'])->default('tabular');
            $table->unsignedInteger('version')->default(1);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::connection('pgsql')->create('bioestadistica.form_secciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('formulario_id')->constrained('bioestadistica.formularios')->cascadeOnDelete();
            $table->string('titulo', 250);
            $table->text('descripcion')->nullable();
            $table->integer('orden')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->index(['formulario_id', 'orden']);
        });

        Schema::connection('pgsql')->create('bioestadistica.fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seccion_id')->constrained('bioestadistica.form_secciones')->cascadeOnDelete();
            $table->string('code', 100);
            $table->string('label', 400);
            $table->enum('type', [
                'text', 'textarea', 'integer', 'decimal', 'date', 'time', 'boolean',
                'select', 'multiselect', 'radio', 'tabla', 'subtabla', 'matriz',
            ]);
            $table->boolean('required')->default(false);
            $table->decimal('min_value', 18, 4)->nullable();
            $table->decimal('max_value', 18, 4)->nullable();
            $table->string('validation_regex', 500)->nullable();
            $table->string('tooltip', 400)->nullable();
            $table->text('help_text')->nullable();
            $table->foreignId('catalogo_id')->nullable()->constrained('bioestadistica.catalogos')->nullOnDelete();
            $table->foreignId('parent_field_id')->nullable()->constrained('bioestadistica.fields')->cascadeOnDelete();
            $table->foreignId('variable_definition_id')->nullable()->constrained('bioestadistica.variable_definitions')->nullOnDelete();
            $table->jsonb('config')->nullable();
            $table->integer('orden')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['seccion_id', 'code']);
            $table->index(['seccion_id', 'orden']);
            $table->index('parent_field_id');
        });
    }

    public function down(): void
    {
        Schema::connection('pgsql')->dropIfExists('bioestadistica.fields');
        Schema::connection('pgsql')->dropIfExists('bioestadistica.form_secciones');
        Schema::connection('pgsql')->dropIfExists('bioestadistica.formularios');
        Schema::connection('pgsql')->dropIfExists('bioestadistica.variable_definitions');
        Schema::connection('pgsql')->dropIfExists('bioestadistica.catalog_items');
        Schema::connection('pgsql')->dropIfExists('bioestadistica.catalogos');
    }
};
