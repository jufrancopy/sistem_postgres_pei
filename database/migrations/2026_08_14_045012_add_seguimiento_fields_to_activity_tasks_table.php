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
        if (!Schema::hasColumn('activity_tasks', 'es_seguimiento')) {
            Schema::table('activity_tasks', function (Blueprint $table) {
                $table->boolean('es_seguimiento')->default(false);
                $table->string('nro_expediente')->nullable();
                $table->string('destino_dependencia')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activity_tasks', function (Blueprint $table) {
            $table->dropColumn(['es_seguimiento', 'nro_expediente', 'destino_dependencia']);
        });
    }
};
