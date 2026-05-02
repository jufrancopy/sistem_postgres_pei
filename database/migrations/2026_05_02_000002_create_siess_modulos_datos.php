<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ══════════════════════════════════════════════════════════════════════
        // MÓDULO 1 — AOP: Aportes y Trabajadores
        // ══════════════════════════════════════════════════════════════════════

        // AOP5 — Trabajadores Activos
        Schema::connection('pgsql')->create('estadistica.aop_trabajadores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('extracto_id')->constrained('estadistica.siess_extractos')->cascadeOnDelete();
            $table->foreignId('periodo_id')->constrained('estadistica.siess_periodos');

            // Datos del trabajador
            $table->string('cedula', 20)->nullable();
            $table->tinyInteger('edad')->nullable();
            $table->enum('sexo', ['M', 'F', 'otro'])->nullable();
            $table->decimal('salario', 18, 2)->nullable();
            $table->enum('tipo_empleado', ['publico', 'privado'])->nullable();

            // Seguro
            $table->string('tipo_seguro_codigo', 10)->nullable();
            $table->string('tipo_seguro_descripcion')->nullable();
            $table->boolean('es_excombatiente')->default(false);

            // Régimen
            $table->string('regimen_codigo', 10)->nullable();
            $table->string('regimen_descripcion')->nullable();

            // Ubicación
            $table->string('departamento_codigo', 5)->nullable();
            $table->string('departamento_nombre')->nullable();
            $table->string('zona')->nullable();

            // Empleador
            $table->string('empleador_ruc', 20)->nullable();
            $table->string('empleador_nro_patronal', 20)->nullable();
            $table->string('empleador_descripcion')->nullable();
            $table->string('empleador_actividad')->nullable();

            // Aportes
            $table->decimal('aporte_empleado', 18, 2)->nullable();
            $table->decimal('aporte_patronal', 18, 2)->nullable();
            $table->decimal('complemento_salud', 18, 2)->nullable();

            $table->timestamps();
            $table->index(['periodo_id', 'tipo_empleado']);
            $table->index(['periodo_id', 'departamento_codigo']);
        });

        // AOP6 — Empleadores
        Schema::connection('pgsql')->create('estadistica.aop_empleadores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('extracto_id')->constrained('estadistica.siess_extractos')->cascadeOnDelete();
            $table->foreignId('periodo_id')->constrained('estadistica.siess_periodos');

            $table->string('ruc', 20)->nullable();
            $table->string('nro_patronal', 20)->nullable();
            $table->string('descripcion')->nullable();
            $table->string('actividad')->nullable();
            $table->integer('empleados_activos')->default(0);
            $table->string('estado')->nullable();
            $table->string('departamento_codigo', 5)->nullable();
            $table->string('departamento_nombre')->nullable();
            $table->string('zona')->nullable();
            $table->decimal('aportes_total', 18, 2)->default(0);

            $table->timestamps();
        });

        // AOP7 — Recaudación
        Schema::connection('pgsql')->create('estadistica.aop_recaudacion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('extracto_id')->constrained('estadistica.siess_extractos')->cascadeOnDelete();
            $table->foreignId('periodo_id')->constrained('estadistica.siess_periodos');

            $table->string('departamento_codigo', 5)->nullable();
            $table->string('departamento_nombre')->nullable();
            $table->string('distrito')->nullable();
            $table->decimal('monto_empleado', 18, 2)->default(0);
            $table->decimal('monto_empleador', 18, 2)->default(0);
            $table->decimal('monto_total', 18, 2)->storedAs('monto_empleado + monto_empleador');

            $table->timestamps();
        });

        // AOP8 — Mora
        Schema::connection('pgsql')->create('estadistica.aop_mora', function (Blueprint $table) {
            $table->id();
            $table->foreignId('extracto_id')->constrained('estadistica.siess_extractos')->cascadeOnDelete();
            $table->foreignId('periodo_id')->constrained('estadistica.siess_periodos');

            $table->string('empleador_ruc', 20)->nullable();
            $table->string('empleador_descripcion')->nullable();
            $table->decimal('monto_planillas_normales', 18, 2)->default(0);
            $table->decimal('monto_complementarias', 18, 2)->default(0);
            $table->decimal('monto_fraccionamiento', 18, 2)->default(0);
            $table->integer('dias_mora')->default(0);

            $table->timestamps();
        });

        // ══════════════════════════════════════════════════════════════════════
        // MÓDULO 3 — JU: Jubilaciones y Pensiones
        // ══════════════════════════════════════════════════════════════════════

        // JU1 — Beneficiarios
        Schema::connection('pgsql')->create('estadistica.ju_beneficiarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('extracto_id')->constrained('estadistica.siess_extractos')->cascadeOnDelete();
            $table->foreignId('periodo_id')->constrained('estadistica.siess_periodos');

            $table->string('cedula', 20)->nullable();
            $table->enum('sexo', ['M', 'F', 'otro'])->nullable();
            $table->tinyInteger('edad')->nullable();
            $table->string('barrio')->nullable();
            $table->string('ciudad')->nullable();
            $table->string('departamento_codigo', 5)->nullable();
            $table->string('departamento_nombre')->nullable();
            $table->decimal('monto_bruto', 18, 2)->nullable();
            $table->string('concepto')->nullable(); // Vejez, Invalidez, Fallecimiento, Ley 4290/11, etc.
            $table->date('fecha_concesion')->nullable();

            $table->timestamps();
            $table->index(['periodo_id', 'concepto']);
            $table->index(['periodo_id', 'departamento_codigo']);
        });

        // JU2/JU3 — Altas y Solicitudes
        Schema::connection('pgsql')->create('estadistica.ju_altas_solicitudes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('extracto_id')->constrained('estadistica.siess_extractos')->cascadeOnDelete();
            $table->foreignId('periodo_id')->constrained('estadistica.siess_periodos');

            $table->enum('tipo', ['alta', 'solicitud']);
            $table->string('concepto')->nullable();
            $table->integer('cantidad')->default(0);
            $table->decimal('tiempo_promedio_dias', 8, 2)->nullable();
            $table->integer('tiempo_minimo_dias')->nullable();
            $table->integer('tiempo_maximo_dias')->nullable();

            $table->timestamps();
        });

        // PL4 — Financiero Jubilaciones (relación activo/pasivo)
        Schema::connection('pgsql')->create('estadistica.pl_financiero', function (Blueprint $table) {
            $table->id();
            $table->foreignId('extracto_id')->constrained('estadistica.siess_extractos')->cascadeOnDelete();
            $table->foreignId('periodo_id')->constrained('estadistica.siess_periodos');

            $table->integer('total_activos')->default(0);   // cotizantes
            $table->integer('total_pasivos')->default(0);   // jubilados/pensionados
            $table->decimal('relacion_activo_pasivo', 8, 4)->nullable();
            $table->decimal('tasa_activo_pasivo', 8, 4)->nullable();

            $table->timestamps();
        });

        // ══════════════════════════════════════════════════════════════════════
        // MÓDULO 6 — DT/DCP: Tesorería y Contabilidad
        // ══════════════════════════════════════════════════════════════════════

        // DCP — Ejecución Presupuestaria
        Schema::connection('pgsql')->create('estadistica.dcp_presupuesto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('extracto_id')->constrained('estadistica.siess_extractos')->cascadeOnDelete();
            $table->foreignId('periodo_id')->constrained('estadistica.siess_periodos');

            $table->enum('tipo', ['ingreso', 'egreso']);
            $table->string('concepto');
            $table->string('objeto_gasto')->nullable();
            $table->decimal('presupuestado', 18, 2)->default(0);
            $table->decimal('ejecutado', 18, 2)->default(0);
            $table->decimal('pct_ejecucion', 8, 4)
                ->storedAs('CASE WHEN presupuestado > 0 THEN ROUND((ejecutado / presupuestado * 100)::numeric, 4) ELSE 0 END');

            $table->timestamps();
            $table->index(['periodo_id', 'tipo']);
        });

        // DT — Tesorería (pagos)
        Schema::connection('pgsql')->create('estadistica.dt_tesoreria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('extracto_id')->constrained('estadistica.siess_extractos')->cascadeOnDelete();
            $table->foreignId('periodo_id')->constrained('estadistica.siess_periodos');

            $table->enum('tipo', ['subsidios', 'proveedores', 'jubilados', 'depositos', 'otros']);
            $table->string('descripcion')->nullable();
            $table->decimal('monto', 18, 2)->default(0);
            $table->integer('cantidad_operaciones')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection('pgsql')->dropIfExists('estadistica.dt_tesoreria');
        Schema::connection('pgsql')->dropIfExists('estadistica.dcp_presupuesto');
        Schema::connection('pgsql')->dropIfExists('estadistica.pl_financiero');
        Schema::connection('pgsql')->dropIfExists('estadistica.ju_altas_solicitudes');
        Schema::connection('pgsql')->dropIfExists('estadistica.ju_beneficiarios');
        Schema::connection('pgsql')->dropIfExists('estadistica.aop_mora');
        Schema::connection('pgsql')->dropIfExists('estadistica.aop_recaudacion');
        Schema::connection('pgsql')->dropIfExists('estadistica.aop_empleadores');
        Schema::connection('pgsql')->dropIfExists('estadistica.aop_trabajadores');
    }
};
