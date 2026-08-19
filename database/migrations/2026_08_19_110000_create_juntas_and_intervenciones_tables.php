<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabla de Juntas (Consejo de Sabios)
        if (!Schema::hasTable('planificacion.juntas')) {
            Schema::create('planificacion.juntas', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('nombre');
                $table->string('codigo')->unique();
                $table->enum('programa', ['salud', 'jubilaciones', 'finanzas', 'institucional'])->default('salud');
                $table->text('descripcion')->nullable();
                $table->string('presidente_nombre');
                $table->string('presidente_cargo')->default('Presidente de la Junta Consultiva');
                $table->text('firma_digital_url')->nullable();
                $table->text('sello_institucional_url')->nullable();
                $table->boolean('activo')->default(true);
                $table->timestamps();
            });
        }

        // 2. Agregar junta_id a pei_profiles
        if (Schema::hasTable('planificacion.pei_profiles') && !Schema::hasColumn('planificacion.pei_profiles', 'junta_id')) {
            Schema::table('planificacion.pei_profiles', function (Blueprint $table) {
                $table->uuid('junta_id')->nullable();
                $table->foreign('junta_id')->references('id')->on('planificacion.juntas')->onDelete('set null');
            });
        }

        // 3. Tabla de Intervenciones / Dictámenes de Junta
        if (!Schema::hasTable('planificacion.junta_intervenciones')) {
            Schema::create('planificacion.junta_intervenciones', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('codigo_expediente')->unique();
                $table->uuid('junta_id');
                $table->uuid('pei_profile_id');
                $table->unsignedBigInteger('reporte_avance_id')->nullable();
                $table->unsignedBigInteger('solicitante_user_id');
                $table->text('diagnostico')->nullable();
                $table->jsonb('recomendaciones_mitigacion')->nullable();
                $table->string('estado')->default('PENDIENTE'); // PENDIENTE, EMITIDO, EN_APLICACION
                $table->string('prioridad')->default('ALTA');   // MEDIA, ALTA, EMERGENCIA
                $table->timestamp('firma_estampada_at')->nullable();
                $table->timestamps();

                $table->foreign('junta_id')->references('id')->on('planificacion.juntas')->onDelete('cascade');
                $table->foreign('pei_profile_id')->references('id')->on('planificacion.pei_profiles')->onDelete('cascade');
                $table->foreign('reporte_avance_id')->references('id')->on('planificacion.pei_accion_reportes')->onDelete('cascade');
                $table->foreign('solicitante_user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('planificacion.junta_intervenciones');
        if (Schema::hasColumn('planificacion.pei_profiles', 'junta_id')) {
            Schema::table('planificacion.pei_profiles', function (Blueprint $table) {
                $table->dropForeign(['junta_id']);
                $table->dropColumn('junta_id');
            });
        }
        Schema::dropIfExists('planificacion.juntas');
    }
};
