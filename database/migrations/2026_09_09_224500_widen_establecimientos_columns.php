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
        Schema::table('establecimientos', function (Blueprint $table) {
            $table->string('tipologia_clasificacion', 150)->nullable()->change();
            $table->string('observacion', 255)->nullable()->change();
            $table->string('sistema_hospitalario', 60)->nullable()->change();
            $table->string('prestador', 60)->nullable()->change();
            $table->string('microred', 120)->nullable()->change();
            $table->string('departamento', 80)->nullable()->change();
            $table->string('complejidad', 150)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('establecimientos', function (Blueprint $table) {
            $table->string('tipologia_clasificacion', 40)->nullable()->change();
        });
    }
};
