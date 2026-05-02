<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // ── Períodos ──────────────────────────────────────────────────────────
        Schema::connection('pgsql')->create('estadistica.siess_periodos', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('anio');
            $table->tinyInteger('mes')->nullable()->comment('null = anual');
            $table->enum('tipo', ['mensual', 'anual', 'trimestral', 'semestral'])->default('mensual');
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->unique(['anio', 'mes', 'tipo']);
        });

        // ── Módulos SIESS ─────────────────────────────────────────────────────
        Schema::connection('pgsql')->create('estadistica.siess_modulos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 10)->unique(); // AOP, PL, JU, RL, DI, DT, RH, INF, CAU
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->enum('periodicidad', ['mensual', 'anual', 'mensual_anual']);
            $table->boolean('activo')->default(true);
            $table->integer('orden')->default(0);
            $table->timestamps();
        });

        // ── Indicadores por módulo ────────────────────────────────────────────
        Schema::connection('pgsql')->create('estadistica.siess_indicadores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('modulo_id')->constrained('estadistica.siess_modulos')->cascadeOnDelete();
            $table->string('codigo', 20)->unique(); // AOP5, JU1, RL1...
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->string('unidad')->nullable(); // personas, PYG, %, etc.
            $table->enum('tipo_carga', ['manual', 'automatica', 'mixta'])->default('manual');
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        // ── Fuentes de datos ──────────────────────────────────────────────────
        Schema::connection('pgsql')->create('estadistica.siess_fuentes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->enum('tipo', ['manual', 'api', 'extraccion_bd', 'archivo']);
            $table->string('sistema_origen')->nullable(); // SAP, SICO, etc.
            $table->string('endpoint_url')->nullable();
            $table->text('descripcion')->nullable();
            $table->boolean('activa')->default(true);
            $table->timestamps();
        });

        // ── Extractos (tabla central del flujo de validación) ─────────────────
        Schema::connection('pgsql')->create('estadistica.siess_extractos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('modulo_id')->constrained('estadistica.siess_modulos');
            $table->foreignId('indicador_id')->constrained('estadistica.siess_indicadores');
            $table->foreignId('periodo_id')->constrained('estadistica.siess_periodos');
            $table->foreignId('fuente_id')->nullable()->constrained('estadistica.siess_fuentes')->nullOnDelete();

            // Dirección responsable (FK a organigramas)
            $table->unsignedInteger('direccion_id')->nullable();
            $table->foreign('direccion_id')->references('id')->on('organigramas')->nullOnDelete();

            // Máquina de estados (Art. 8 Res. 266/2022)
            $table->enum('estado', [
                'borrador',
                'pendiente_validacion',
                'aprobado',
                'objetado',
                'aprobado_silencio',
            ])->default('borrador');

            // Datos del extracto en formato flexible
            $table->jsonb('datos')->nullable();
            $table->text('resumen')->nullable(); // descripción ejecutiva

            // Trazabilidad
            $table->unsignedInteger('cargado_por')->nullable();
            $table->foreign('cargado_por')->references('id')->on('users')->nullOnDelete();
            $table->unsignedInteger('aprobado_por')->nullable();
            $table->foreign('aprobado_por')->references('id')->on('users')->nullOnDelete();

            // Fechas del flujo
            $table->timestamp('fecha_envio_validacion')->nullable();
            $table->timestamp('fecha_limite_validacion')->nullable(); // +5 días hábiles
            $table->timestamp('fecha_respuesta')->nullable();
            $table->text('observacion_objecion')->nullable();

            // Fuente única (Art. 6)
            $table->boolean('es_fuente_unica')->default(false);
            $table->timestamp('fecha_fuente_unica')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['estado', 'fecha_limite_validacion']); // para el job de silencio
            $table->index(['modulo_id', 'periodo_id']);
        });

        // ── Log de validaciones (auditoría completa) ──────────────────────────
        Schema::connection('pgsql')->create('estadistica.siess_validaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('extracto_id')->constrained('estadistica.siess_extractos')->cascadeOnDelete();
            $table->enum('accion', ['envio', 'aprobacion', 'objecion', 'silencio', 'reenvio', 'fuente_unica']);
            $table->unsignedInteger('usuario_id')->nullable();
            $table->foreign('usuario_id')->references('id')->on('users')->nullOnDelete();
            $table->text('comentario')->nullable();
            $table->jsonb('metadata')->nullable(); // estado_anterior, estado_nuevo, etc.
            $table->timestamp('fecha');
            $table->timestamps();
        });

        // ── Sembrar módulos base ──────────────────────────────────────────────
        DB::connection('pgsql')->table('estadistica.siess_modulos')->insert([
            ['codigo' => 'AOP', 'nombre' => 'Aportes y Trabajadores',              'periodicidad' => 'mensual',       'orden' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['codigo' => 'PL',  'nombre' => 'Poblacional',                          'periodicidad' => 'anual',         'orden' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['codigo' => 'JU',  'nombre' => 'Jubilaciones y Pensiones',             'periodicidad' => 'mensual_anual', 'orden' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['codigo' => 'RL',  'nombre' => 'Subsidios y Riesgo Laboral',           'periodicidad' => 'mensual',       'orden' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['codigo' => 'DI',  'nombre' => 'Ingresos, Gastos e Inversiones',       'periodicidad' => 'mensual',       'orden' => 5, 'created_at' => now(), 'updated_at' => now()],
            ['codigo' => 'DT',  'nombre' => 'Tesorería y Contabilidad',             'periodicidad' => 'mensual_anual', 'orden' => 6, 'created_at' => now(), 'updated_at' => now()],
            ['codigo' => 'RH',  'nombre' => 'Recursos Humanos',                     'periodicidad' => 'mensual_anual', 'orden' => 7, 'created_at' => now(), 'updated_at' => now()],
            ['codigo' => 'INF', 'nombre' => 'Servicios Generales e Infraestructura','periodicidad' => 'mensual',       'orden' => 8, 'created_at' => now(), 'updated_at' => now()],
            ['codigo' => 'CAU', 'nombre' => 'Suministros de Salud y Atención',      'periodicidad' => 'mensual',       'orden' => 9, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // ── Sembrar indicadores base ──────────────────────────────────────────
        $modulos = DB::connection('pgsql')->table('estadistica.siess_modulos')->pluck('id', 'codigo');

        $indicadores = [
            // AOP
            ['modulo_id' => $modulos['AOP'], 'codigo' => 'AOP5', 'nombre' => 'Trabajadores Activos',    'unidad' => 'personas',  'tipo_carga' => 'automatica'],
            ['modulo_id' => $modulos['AOP'], 'codigo' => 'AOP6', 'nombre' => 'Empleadores',             'unidad' => 'empresas',  'tipo_carga' => 'automatica'],
            ['modulo_id' => $modulos['AOP'], 'codigo' => 'AOP7', 'nombre' => 'Recaudación',             'unidad' => 'PYG',       'tipo_carga' => 'automatica'],
            ['modulo_id' => $modulos['AOP'], 'codigo' => 'AOP8', 'nombre' => 'Mora',                    'unidad' => 'PYG',       'tipo_carga' => 'manual'],
            // PL
            ['modulo_id' => $modulos['PL'],  'codigo' => 'PL1',  'nombre' => 'Estructura de Asegurados','unidad' => 'personas',  'tipo_carga' => 'manual'],
            ['modulo_id' => $modulos['PL'],  'codigo' => 'PL2',  'nombre' => 'Histórico Poblacional',   'unidad' => 'personas',  'tipo_carga' => 'manual'],
            ['modulo_id' => $modulos['PL'],  'codigo' => 'PL4',  'nombre' => 'Financiero Jubilaciones', 'unidad' => 'ratio',     'tipo_carga' => 'manual'],
            // JU
            ['modulo_id' => $modulos['JU'],  'codigo' => 'JU1',  'nombre' => 'Beneficiarios',           'unidad' => 'personas',  'tipo_carga' => 'automatica'],
            ['modulo_id' => $modulos['JU'],  'codigo' => 'JU2',  'nombre' => 'Altas de Jubilación',     'unidad' => 'casos',     'tipo_carga' => 'manual'],
            ['modulo_id' => $modulos['JU'],  'codigo' => 'JU3',  'nombre' => 'Solicitudes Pendientes',  'unidad' => 'casos',     'tipo_carga' => 'manual'],
            // RL
            ['modulo_id' => $modulos['RL'],  'codigo' => 'RL1',  'nombre' => 'Subsidios',               'unidad' => 'PYG',       'tipo_carga' => 'automatica'],
            // DI
            ['modulo_id' => $modulos['DI'],  'codigo' => 'DI1',  'nombre' => 'Portafolio de Inversiones','unidad' => 'PYG',      'tipo_carga' => 'manual'],
            ['modulo_id' => $modulos['DI'],  'codigo' => 'DI11', 'nombre' => 'Préstamos de Caja',       'unidad' => 'PYG',       'tipo_carga' => 'manual'],
            ['modulo_id' => $modulos['DI'],  'codigo' => 'DI12', 'nombre' => 'Bienes Inmuebles',        'unidad' => 'm2/PYG',    'tipo_carga' => 'manual'],
            // DT
            ['modulo_id' => $modulos['DT'],  'codigo' => 'DT1',  'nombre' => 'Tesorería',               'unidad' => 'PYG',       'tipo_carga' => 'manual'],
            ['modulo_id' => $modulos['DT'],  'codigo' => 'DCP1', 'nombre' => 'Ejecución Presupuestaria','unidad' => 'PYG',       'tipo_carga' => 'manual'],
            // RH
            ['modulo_id' => $modulos['RH'],  'codigo' => 'RH1',  'nombre' => 'Nómina de Funcionarios',  'unidad' => 'personas',  'tipo_carga' => 'automatica'],
            ['modulo_id' => $modulos['RH'],  'codigo' => 'RH3',  'nombre' => 'Movimientos de Personal', 'unidad' => 'casos',     'tipo_carga' => 'manual'],
            // INF
            ['modulo_id' => $modulos['INF'], 'codigo' => 'INF1', 'nombre' => 'Proyectos de Infraestructura','unidad' => 'PYG/%','tipo_carga' => 'manual'],
            ['modulo_id' => $modulos['INF'], 'codigo' => 'GA1',  'nombre' => 'Servicios Generales',     'unidad' => 'PYG',       'tipo_carga' => 'manual'],
            // CAU
            ['modulo_id' => $modulos['CAU'], 'codigo' => 'ARLG01','nombre' => 'Suministros de Salud',   'unidad' => 'items',     'tipo_carga' => 'manual'],
            ['modulo_id' => $modulos['CAU'], 'codigo' => 'CAU1', 'nombre' => 'Atención al Usuario',     'unidad' => 'contactos', 'tipo_carga' => 'automatica'],
        ];

        foreach ($indicadores as $ind) {
            DB::connection('pgsql')->table('estadistica.siess_indicadores')->insert(
                array_merge($ind, ['created_at' => now(), 'updated_at' => now()])
            );
        }
    }

    public function down(): void
    {
        Schema::connection('pgsql')->dropIfExists('estadistica.siess_validaciones');
        Schema::connection('pgsql')->dropIfExists('estadistica.siess_extractos');
        Schema::connection('pgsql')->dropIfExists('estadistica.siess_fuentes');
        Schema::connection('pgsql')->dropIfExists('estadistica.siess_indicadores');
        Schema::connection('pgsql')->dropIfExists('estadistica.siess_modulos');
        Schema::connection('pgsql')->dropIfExists('estadistica.siess_periodos');
    }
};
