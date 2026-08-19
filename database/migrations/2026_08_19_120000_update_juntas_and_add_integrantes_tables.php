<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Agregar campos extra a planificacion.juntas
        if (Schema::hasTable('planificacion.juntas')) {
            Schema::table('planificacion.juntas', function (Blueprint $table) {
                if (!Schema::hasColumn('planificacion.juntas', 'fines')) {
                    $table->text('fines')->nullable();
                }
                if (!Schema::hasColumn('planificacion.juntas', 'atribuciones')) {
                    $table->text('atribuciones')->nullable();
                }
                if (!Schema::hasColumn('planificacion.juntas', 'ambito_competencia')) {
                    $table->string('ambito_competencia')->nullable();
                }
                if (!Schema::hasColumn('planificacion.juntas', 'presidente_user_id')) {
                    $table->unsignedBigInteger('presidente_user_id')->nullable();
                    $table->foreign('presidente_user_id')->references('id')->on('users')->onDelete('set null');
                }
            });
        }

        // 2. Tabla Pivot Junta - Integrantes (Usuarios)
        if (!Schema::hasTable('planificacion.junta_integrantes')) {
            Schema::create('planificacion.junta_integrantes', function (Blueprint $table) {
                $table->uuid('junta_id');
                $table->unsignedBigInteger('user_id');
                $table->string('cargo')->nullable()->default('Miembro Consultor');
                $table->timestamps();

                $table->primary(['junta_id', 'user_id']);
                $table->foreign('junta_id')->references('id')->on('planificacion.juntas')->onDelete('cascade');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }

        // 3. Tabla Pivot PEI Profile (Objetivos / Acciones) - Juntas (Relación Muchos a Muchos)
        if (!Schema::hasTable('planificacion.pei_profile_juntas')) {
            Schema::create('planificacion.pei_profile_juntas', function (Blueprint $table) {
                $table->uuid('pei_profile_id');
                $table->uuid('junta_id');
                $table->timestamps();

                $table->primary(['pei_profile_id', 'junta_id']);
                $table->foreign('pei_profile_id')->references('id')->on('planificacion.pei_profiles')->onDelete('cascade');
                $table->foreign('junta_id')->references('id')->on('planificacion.juntas')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('planificacion.pei_profile_juntas');
        Schema::dropIfExists('planificacion.junta_integrantes');

        if (Schema::hasTable('planificacion.juntas')) {
            Schema::table('planificacion.juntas', function (Blueprint $table) {
                if (Schema::hasColumn('planificacion.juntas', 'presidente_user_id')) {
                    $table->dropForeign(['presidente_user_id']);
                    $table->dropColumn('presidente_user_id');
                }
                $table->dropColumn(['fines', 'atribuciones', 'ambito_competencia']);
            });
        }
    }
};
