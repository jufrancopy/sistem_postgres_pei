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
        // 1. Tabla para historial y transacciones de puntos
        if (!Schema::hasTable('gamification_points')) {
            Schema::create('gamification_points', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->index();
                $table->uuid('pei_profile_id')->nullable()->index();
                $table->integer('points');
                $table->string('action_type')->index(); // task_created, task_completed, foda_analisis, foda_cruce, riiss_evaluacion, comment_created, daily_login
                $table->string('description');
                $table->string('reference_type')->nullable();
                $table->string('reference_id')->nullable();
                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }

        // 2. Tabla para insignias (badges) desbloqueadas por el usuario
        if (!Schema::hasTable('gamification_user_badges')) {
            Schema::create('gamification_user_badges', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->index();
                $table->uuid('pei_profile_id')->nullable()->index();
                $table->string('badge_key')->index(); // primer_comentario, analista_foda, etc.
                $table->timestamp('unlocked_at');
                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->unique(['user_id', 'pei_profile_id', 'badge_key'], 'unique_user_pei_badge');
            });
        }

        // 3. Tabla para controlar ingresos diarios del usuario (rachas y accesos)
        if (!Schema::hasTable('user_logins')) {
            Schema::create('user_logins', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->index();
                $table->date('login_date')->index();
                $table->string('ip_address')->nullable();
                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->unique(['user_id', 'login_date']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_logins');
        Schema::dropIfExists('gamification_user_badges');
        Schema::dropIfExists('gamification_points');
    }
};
