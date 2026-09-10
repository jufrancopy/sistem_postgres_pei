<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('riiss_sesiones_validador', 'area_gestion')) {
            Schema::table('riiss_sesiones_validador', function (Blueprint $table) {
                $table->string('area_gestion', 50)->default('AREA INTERIOR')->after('departamento_filtro');
            });
        }

        // Sincronizar y estandarizar establecimientos según lámina oficial de Área Central vs Área Interior
        // 1. Asignar AREA CENTRAL a todos los de Asunción, Central y Capital
        DB::table('establecimientos')
            ->whereIn('departamento', ['CENTRAL', 'ASUNCIÓN', 'CAPITAL'])
            ->update(['area_gestion' => 'AREA CENTRAL']);

        // 2. Asignar AREA INTERIOR a todos los demás departamentos
        DB::table('establecimientos')
            ->whereNotIn('departamento', ['CENTRAL', 'ASUNCIÓN', 'CAPITAL'])
            ->update(['area_gestion' => 'AREA INTERIOR']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('riiss_sesiones_validador', 'area_gestion')) {
            Schema::table('riiss_sesiones_validador', function (Blueprint $table) {
                $table->dropColumn('area_gestion');
            });
        }
    }
};
