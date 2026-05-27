<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── Plan Maestro ─────────────────────────────────────────────────────
        Schema::create('plan_maestros', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('institucion');
            $table->text('descripcion')->nullable();
            $table->string('responsable')->nullable();
            $table->string('periodo')->nullable(); // ej: "2026 | Primeros 100 días"
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        // ── Ejes estratégicos ────────────────────────────────────────────────
        Schema::create('plan_ejes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')->constrained('plan_maestros')->cascadeOnDelete();
            $table->string('codigo', 5);  // A, B, C...
            $table->string('nombre');
            $table->string('color', 20)->default('#2a9d8f');
            $table->string('icono', 50)->default('fa-circle');
            $table->integer('orden')->default(0);
            $table->timestamps();
        });

        // ── Acciones del plan ────────────────────────────────────────────────
        Schema::create('plan_acciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')->constrained('plan_maestros')->cascadeOnDelete();
            $table->foreignId('eje_id')->constrained('plan_ejes')->cascadeOnDelete();
            $table->string('codigo', 20);   // A-01, B-03...
            $table->string('momento', 10);  // T0, T1, T2, T3, T4, T5, TX
            $table->text('accion');
            $table->text('justificacion')->nullable();
            $table->text('kpi')->nullable();
            $table->string('plazo')->nullable();
            $table->string('responsable')->nullable();
            $table->string('estado')->nullable();
            $table->text('detalle')->nullable();
            $table->integer('orden')->default(0);
            $table->timestamps();
        });

        // ── Diagnósticos ─────────────────────────────────────────────────────
        Schema::create('plan_diagnosticos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')->constrained('plan_maestros')->cascadeOnDelete();
            $table->string('titulo');
            $table->text('contenido');
            $table->integer('orden')->default(0);
            $table->timestamps();
        });

        Schema::create('plan_diagnostico_tags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('diagnostico_id')->constrained('plan_diagnosticos')->cascadeOnDelete();
            $table->string('tag');
        });

        // ── Canillas de fuga ─────────────────────────────────────────────────
        Schema::create('plan_canillas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')->constrained('plan_maestros')->cascadeOnDelete();
            $table->string('tipo');
            $table->text('ejemplos')->nullable();
            $table->text('estrategia')->nullable();
            $table->string('monto')->nullable();
            $table->integer('orden')->default(0);
            $table->timestamps();
        });

        // ── Citas destacadas ─────────────────────────────────────────────────
        Schema::create('plan_citas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')->constrained('plan_maestros')->cascadeOnDelete();
            $table->text('texto');
            $table->string('autor');
            $table->string('fecha')->nullable();
            $table->text('contexto')->nullable();
            $table->integer('orden')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plan_citas');
        Schema::dropIfExists('plan_canillas');
        Schema::dropIfExists('plan_diagnostico_tags');
        Schema::dropIfExists('plan_diagnosticos');
        Schema::dropIfExists('plan_acciones');
        Schema::dropIfExists('plan_ejes');
        Schema::dropIfExists('plan_maestros');
    }
};
