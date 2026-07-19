<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('planificacion.pei_actores', function (Blueprint $table) {
            $table->id();
            $table->uuid('pei_profile_id');
            $table->enum('tipo', ['interno', 'externo'])->default('interno');

            // Interno
            $table->unsignedBigInteger('organigrama_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();

            // Externo
            $table->string('dependencia_externa')->nullable();
            $table->string('persona_referente')->nullable();
            $table->string('email_externo')->nullable();

            $table->text('aportes')->nullable();
            $table->unsignedSmallInteger('orden')->default(0);
            $table->timestamps();

            $table->foreign('pei_profile_id')->references('id')->on('planificacion.pei_profiles')->onDelete('cascade');
            $table->foreign('organigrama_id')->references('id')->on('organigramas')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('planificacion.pei_actores');
    }
};
