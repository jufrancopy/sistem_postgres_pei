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
        Schema::create('planificacion.relevamiento_procesos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nombre');
            $table->text('contexto_motivo')->nullable();
            $table->uuid('pei_profile_id')->nullable();
            $table->unsignedBigInteger('organigrama_id')->nullable();
            $table->string('tipo_relevamiento')->default('circuito_paciente');
            $table->string('estado')->default('en_relevamiento');
            $table->date('fecha_relevamiento')->nullable();
            $table->text('objetivo')->nullable();
            $table->text('analisis_ia')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('pei_profile_id')->references('id')->on('planificacion.pei_profiles')->onDelete('set null');
            $table->foreign('organigrama_id')->references('id')->on('organigramas')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });

        Schema::create('planificacion.relevamiento_proceso_responsables', function (Blueprint $table) {
            $table->uuid('relevamiento_proceso_id');
            $table->unsignedBigInteger('user_id');
            $table->string('rol_visita')->default('Analista');

            $table->primary(['relevamiento_proceso_id', 'user_id']);
            $table->foreign('relevamiento_proceso_id', 'fk_rel_proc_resp_proc')
                  ->references('id')->on('planificacion.relevamiento_procesos')->onDelete('cascade');
            $table->foreign('user_id', 'fk_rel_proc_resp_user')
                  ->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('planificacion.relevamiento_pasos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('relevamiento_proceso_id');
            $table->integer('orden')->default(1);
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->unsignedBigInteger('organigrama_id')->nullable();
            $table->string('rol_responsable')->nullable();
            $table->integer('tiempo_atencion_min')->default(0);
            $table->integer('tiempo_espera_min')->default(0);
            $table->integer('tiempo_traslado_min')->default(0);
            $table->string('herramienta_sistema')->nullable();
            $table->boolean('es_cuello_botella')->default(false);
            $table->string('criticidad')->default('baja');
            $table->string('causa_raiz')->nullable();
            $table->text('observacion_campo')->nullable();
            $table->text('propuesta_mejora')->nullable();
            $table->timestamps();

            $table->foreign('relevamiento_proceso_id', 'fk_rel_pasos_proc')
                  ->references('id')->on('planificacion.relevamiento_procesos')->onDelete('cascade');
            $table->foreign('organigrama_id', 'fk_rel_pasos_org')
                  ->references('id')->on('organigramas')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('planificacion.relevamiento_pasos');
        Schema::dropIfExists('planificacion.relevamiento_proceso_responsables');
        Schema::dropIfExists('planificacion.relevamiento_procesos');
    }
};
