<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('system_notifications')) {
            Schema::create('system_notifications', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->index();
                $table->string('tipo')->index();       // puntos_manual, tarea, pei, etc.
                $table->string('titulo');
                $table->text('mensaje');
                $table->string('icono')->default('fa-bell');
                $table->string('url')->nullable();
                $table->boolean('leida')->default(false)->index();
                $table->timestamp('leida_at')->nullable();
                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('system_notifications');
    }
};
