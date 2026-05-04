<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Agrega organigrama_id y locality_id a las tablas SIESS
 * para territorializar los datos (Pilar B — Red Territorial).
 *
 * organigrama_id → el establecimiento/dependencia que generó el dato
 * locality_id    → la ubicación geográfica (departamento/ciudad)
 */
return new class extends Migration
{
    private array $tablas = [
        // Módulo AOP
        'estadistica.aop_trabajadores',
        'estadistica.aop_empleadores',
        'estadistica.aop_recaudacion',
        'estadistica.aop_mora',
        // Módulo JU
        'estadistica.ju_beneficiarios',
        'estadistica.ju_altas_solicitudes',
        'estadistica.pl_financiero',
        // Módulo RL
        'estadistica.rl_subsidios',
        // Módulo DI
        'estadistica.di_portafolio',
        'estadistica.di_prestamos_caja',
        // Módulo DT
        'estadistica.dcp_presupuesto',
        'estadistica.dt_tesoreria',
        // Módulo RH
        'estadistica.rh_nomina',
        'estadistica.rh_movimientos',
        // Módulo INF
        'estadistica.inf_proyectos',
        'estadistica.ga_servicios',
        // Módulo CAU
        'estadistica.sal_suministros',
        'estadistica.cau_atencion',
        // Módulo PL
        'estadistica.pl_estructura',
        'estadistica.pl_historico',
    ];

    public function up(): void
    {
        foreach ($this->tablas as $tabla) {
            Schema::connection('pgsql')->table($tabla, function (Blueprint $table) use ($tabla) {
                // FK al organigrama (establecimiento o dependencia)
                if (!Schema::connection('pgsql')->hasColumn($tabla, 'organigrama_id')) {
                    $table->unsignedInteger('organigrama_id')->nullable()->after('extracto_id');
                    $table->foreign('organigrama_id')
                        ->references('id')->on('organigramas')
                        ->nullOnDelete();
                }

                // FK a locality (territorialización geográfica)
                if (!Schema::connection('pgsql')->hasColumn($tabla, 'locality_id')) {
                    $table->unsignedBigInteger('locality_id')->nullable()->after('organigrama_id');
                    $table->foreign('locality_id')
                        ->references('id')->on('localities')
                        ->nullOnDelete();
                }
            });
        }
    }

    public function down(): void
    {
        foreach ($this->tablas as $tabla) {
            Schema::connection('pgsql')->table($tabla, function (Blueprint $table) use ($tabla) {
                if (Schema::connection('pgsql')->hasColumn($tabla, 'organigrama_id')) {
                    $table->dropForeign([str_replace('.', '_', $tabla) . '_organigrama_id_foreign']);
                    $table->dropColumn('organigrama_id');
                }
                if (Schema::connection('pgsql')->hasColumn($tabla, 'locality_id')) {
                    $table->dropForeign([str_replace('.', '_', $tabla) . '_locality_id_foreign']);
                    $table->dropColumn('locality_id');
                }
            });
        }
    }
};
