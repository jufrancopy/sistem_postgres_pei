<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solicitudes_ajuste_estructura', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique(); // Ej: SAE-2026-0001
            $table->date('fecha_solicitud');
            
            // Vinculación Estratégica Obligatoria con el PEI
            $table->uuid('pei_profile_id')->nullable();
            $table->foreign('pei_profile_id')->references('id')->on('planificacion.pei_profiles')->onDelete('set null');

            // Dependencia Solicitante (Gerencia o Dirección)
            $table->unsignedBigInteger('dependencia_solicitante_id')->nullable();
            $table->foreign('dependencia_solicitante_id')->references('id')->on('organigramas')->onDelete('set null');
            $table->string('dependencia_solicitante_texto')->nullable();

            // Datos del Solicitante
            $table->string('solicitante_nombre');
            $table->string('solicitante_cargo')->nullable();
            $table->string('solicitante_email');
            $table->string('solicitante_telefono')->nullable();

            // Justificación y Respaldo
            $table->text('fundamentacion_general')->nullable();
            $table->string('documento_respaldo_path')->nullable();
            $table->string('organigrama_adjunto_path')->nullable();

            // Estado y Dictamen Técnico
            $table->string('estado')->default('solicitud'); // solicitud, en_analisis, observado, aprobado, rechazado
            $table->text('dictamen_tecnico')->nullable();
            $table->unsignedBigInteger('analista_id')->nullable();
            $table->foreign('analista_id')->references('id')->on('users')->onDelete('set null');
            $table->timestamp('fecha_dictamen')->nullable();

            // Token para compartir y código QR
            $table->string('token_qr', 64)->unique();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('solicitudes_ajuste_estructura_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('solicitud_id');
            $table->foreign('solicitud_id')->references('id')->on('solicitudes_ajuste_estructura')->onDelete('cascade');

            $table->string('tipo_reorganizacion'); // CREACION, MODIFICACION_FUSION, SUPRESION_ELIMINACION, CAMBIO_DENOMINACION, REUBICACION_JERARQUICA, OTRO
            $table->string('denominacion_actual')->nullable();
            $table->string('denominacion_propuesta');
            $table->text('objetivo_dependencia_propuesta');
            $table->text('descripcion_motivos');
            $table->text('observaciones')->nullable();
            $table->integer('orden')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('solicitudes_ajuste_estructura_items');
        Schema::dropIfExists('solicitudes_ajuste_estructura');
    }
};
