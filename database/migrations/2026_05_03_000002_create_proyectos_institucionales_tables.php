<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── Proyectos Institucionales ─────────────────────────────────────────
        Schema::create('proyectos_institucionales', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 20)->unique(); // PROY-2026-001

            $table->string('nombre');
            $table->text('descripcion')->nullable();

            // Máquina de estados
            $table->enum('estado', [
                'solicitud',          // ingresó, pendiente validación alineación PEI
                'en_analisis',        // asistente técnico revisando factibilidad
                'en_desarrollo',      // analista cargando formulario
                'en_homologacion',    // Dirección de Planificación revisando
                'consulta_gerencias', // otras gerencias opinando
                'en_tramite',         // Consejo / firma resolución
                'aprobado',           // resolución firmada
                'en_ejecucion',       // activo, visible para dependencias
                'finalizado',         // cerrado con KPIs
                'rechazado_docs',     // vuelve a la Gerencia Solicitante
                'rechazado_tecnico',  // vuelve al Analista
            ])->default('solicitud');

            // Vinculación con PEI (obligatoria para avanzar)
            $table->uuid('pei_profile_id')->nullable();
            $table->foreign('pei_profile_id')
                ->references('id')->on('planificacion.pei_profiles')
                ->nullOnDelete();

            // Dependencias
            $table->unsignedInteger('dependencia_solicitante_id')->nullable();
            $table->foreign('dependencia_solicitante_id')
                ->references('id')->on('organigramas')->nullOnDelete();

            $table->unsignedInteger('dependencia_ejecutora_id')->nullable();
            $table->foreign('dependencia_ejecutora_id')
                ->references('id')->on('organigramas')->nullOnDelete();

            // Responsables
            $table->unsignedInteger('analista_id')->nullable();
            $table->foreign('analista_id')->references('id')->on('users')->nullOnDelete();

            $table->unsignedInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();

            // Fechas del ciclo de vida
            $table->date('fecha_solicitud')->nullable();
            $table->date('fecha_aprobacion')->nullable();
            $table->date('fecha_inicio_ejecucion')->nullable();
            $table->date('fecha_fin_estimada')->nullable();
            $table->date('fecha_fin_real')->nullable();

            // Presupuesto
            $table->decimal('presupuesto_estimado', 18, 2)->nullable();
            $table->decimal('presupuesto_aprobado', 18, 2)->nullable();
            $table->decimal('presupuesto_ejecutado', 18, 2)->default(0);

            // Resolución
            $table->string('nro_resolucion')->nullable();
            $table->date('fecha_resolucion')->nullable();

            // Rechazo
            $table->text('motivo_rechazo')->nullable();
            $table->enum('tipo_rechazo', ['docs', 'tecnico'])->nullable();

            // Avance
            $table->decimal('avance_pct', 5, 2)->default(0);

            $table->timestamps();
            $table->softDeletes();

            $table->index('estado');
            $table->index('pei_profile_id');
        });

        // ── Checklist del formulario ──────────────────────────────────────────
        Schema::create('proyectos_checklist', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proyecto_id')
                ->constrained('proyectos_institucionales')->cascadeOnDelete();

            $table->enum('item', [
                'matriz_resultados',
                'cronograma',
                'presupuesto_detallado',
                'estudio_factibilidad',
                'documentos_respaldatorios',
                'datos_interes',
                'alineacion_pei',
                'impacto_gerencias',
                'plan_riesgos',
            ]);

            $table->boolean('completado')->default(false);
            $table->string('archivo_url')->nullable();
            $table->text('observacion')->nullable();
            $table->unsignedInteger('completado_por')->nullable();
            $table->foreign('completado_por')->references('id')->on('users')->nullOnDelete();
            $table->timestamp('completado_at')->nullable();

            $table->timestamps();
            $table->unique(['proyecto_id', 'item']);
        });

        // ── Consultas a Gerencias ─────────────────────────────────────────────
        Schema::create('proyectos_consultas_gerencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proyecto_id')
                ->constrained('proyectos_institucionales')->cascadeOnDelete();

            $table->unsignedInteger('organigrama_id');
            $table->foreign('organigrama_id')->references('id')->on('organigramas')->cascadeOnDelete();

            $table->enum('estado', ['pendiente', 'aprobado', 'objetado'])->default('pendiente');
            $table->text('comentario')->nullable();

            $table->unsignedInteger('respondido_por')->nullable();
            $table->foreign('respondido_por')->references('id')->on('users')->nullOnDelete();
            $table->timestamp('fecha_respuesta')->nullable();

            $table->timestamps();
        });

        // ── Historial de estados ──────────────────────────────────────────────
        Schema::create('proyectos_historial', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proyecto_id')
                ->constrained('proyectos_institucionales')->cascadeOnDelete();

            $table->string('estado_anterior')->nullable();
            $table->string('estado_nuevo');
            $table->unsignedInteger('usuario_id')->nullable();
            $table->foreign('usuario_id')->references('id')->on('users')->nullOnDelete();
            $table->text('comentario')->nullable();
            $table->timestamp('fecha');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proyectos_historial');
        Schema::dropIfExists('proyectos_consultas_gerencias');
        Schema::dropIfExists('proyectos_checklist');
        Schema::dropIfExists('proyectos_institucionales');
    }
};
