<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Tabla Principal de Eventos Institucionales
        Schema::create('eventos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nombre');
            $table->string('tipo')->default('Taller'); // Taller, Jornada, Reunión, Congreso, Lanzamiento, etc.
            $table->text('descripcion')->nullable();
            $table->string('lugar_sede')->nullable(); // Ej: Ykua Satí, Auditorio Central, Virtual
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->string('estado')->default('planificado'); // planificado, en_curso, completado, cancelado, en_alerta
            $table->string('color')->default('#6366f1');
            $table->uuid('pei_profile_id')->nullable();
            $table->unsignedBigInteger('activity_id')->nullable();
            $table->unsignedBigInteger('organigrama_id')->nullable();
            $table->decimal('presupuesto_estimado', 15, 2)->default(0);
            $table->decimal('presupuesto_ejecutado', 15, 2)->default(0);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('pei_profile_id')->references('id')->on('planificacion.pei_profiles')->onDelete('set null');
            $table->foreign('activity_id')->references('id')->on('activities')->onDelete('set null');
            $table->foreign('organigrama_id')->references('id')->on('organigramas')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });

        // 2. Tabla Pivote de Responsables / Equipo del Evento
        Schema::create('evento_responsables', function (Blueprint $table) {
            $table->uuid('evento_id');
            $table->unsignedBigInteger('user_id');
            $table->string('rol_evento')->default('Organizador');

            $table->primary(['evento_id', 'user_id']);
            $table->foreign('evento_id')->references('id')->on('eventos')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // 3. Tabla de Pasos / Hitos / Fases Cronológicas del Evento
        Schema::create('evento_pasos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('evento_id');
            $table->integer('orden')->default(1);
            $table->string('nombre'); // Ej: Paso 1: Aprobar Actualización del Plan desde el MEF
            $table->text('descripcion')->nullable();
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->string('estado')->default('pendiente'); // pendiente, en_proceso, completado, en_alerta
            $table->string('color')->nullable();
            $table->unsignedBigInteger('responsable_principal_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('evento_id')->references('id')->on('eventos')->onDelete('cascade');
            $table->foreign('responsable_principal_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });

        // 4. Tabla Pivote de Co-Responsables de Pasos
        Schema::create('evento_paso_responsables', function (Blueprint $table) {
            $table->uuid('evento_paso_id');
            $table->unsignedBigInteger('user_id');

            $table->primary(['evento_paso_id', 'user_id']);
            $table->foreign('evento_paso_id')->references('id')->on('evento_pasos')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        // 5. Tabla de Tareas Operativas / Checklist por Paso
        Schema::create('evento_tareas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('evento_paso_id');
            $table->uuid('evento_id');
            $table->string('nombre'); // Ej: Comprar insumos, Preparar Nota de Invitación
            $table->text('descripcion')->nullable();
            $table->unsignedBigInteger('responsable_id')->nullable();
            $table->date('fecha_limite')->nullable();
            $table->string('prioridad')->default('media'); // baja, media, alta, urgente
            $table->boolean('completada')->default(false);
            $table->timestamp('completada_el')->nullable();
            $table->unsignedBigInteger('completada_por')->nullable();
            $table->decimal('costo_estimado', 15, 2)->default(0);
            $table->integer('orden')->default(0);
            $table->text('observaciones')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('evento_paso_id')->references('id')->on('evento_pasos')->onDelete('cascade');
            $table->foreign('evento_id')->references('id')->on('eventos')->onDelete('cascade');
            $table->foreign('responsable_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('completada_por')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evento_tareas');
        Schema::dropIfExists('evento_paso_responsables');
        Schema::dropIfExists('evento_pasos');
        Schema::dropIfExists('evento_responsables');
        Schema::dropIfExists('eventos');
    }
};
