<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('planificacion.indicadores')) {
            Schema::table('planificacion.indicadores', function (Blueprint $table) {
                if (!Schema::hasColumn('planificacion.indicadores', 'creado_con_ia')) {
                    $table->boolean('creado_con_ia')->default(false)->nullable();
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('planificacion.indicadores')) {
            Schema::table('planificacion.indicadores', function (Blueprint $table) {
                if (Schema::hasColumn('planificacion.indicadores', 'creado_con_ia')) {
                    $table->dropColumn('creado_con_ia');
                }
            });
        }
    }
};
