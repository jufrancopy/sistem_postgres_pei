<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('formulario_secciones', function (Blueprint $table) {
            $table->id();
            $table->string('seccion', 60);
            $table->string('sub_seccion', 120)->nullable();
            $table->integer('orden')->default(0);
            $table->boolean('activa')->default(true);
            $table->timestamps();

            $table->index(['seccion', 'sub_seccion']);
            $table->index('orden');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('formulario_secciones');
    }
};
