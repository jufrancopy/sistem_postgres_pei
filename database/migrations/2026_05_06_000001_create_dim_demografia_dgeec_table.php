<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Tabla de datos demográficos procesados de la DGEEC (INE Paraguay).
 * 
 * Esta tabla NO almacena los registros crudos de la EPHC (~30.000 registros).
 * Almacena los cálculos agregados que la DGEEC publica, cruzados con las
 * variables internas del IPS según el diccionario EPHC.
 * 
 * Variables clave del diccionario EPHC:
 * - Geografía: DPTOREP (Departamento 0-15), AREA (1=Urbana, 6=Rural)
 * - PEA: A02, A03, A04, A05 → alimentan tablas PP5-PP8
 * - Informalidad: B10 (¿Aporta a caja?), B11 (¿Cuál caja? 1=IPS) → tabla AP15
 * - Categoría ocupacional: A15 → cruce con TR2
 * - Ingresos: B16G, B16T → tabla TR4
 * 
 * REGLAS TÉCNICAS:
 * 1. Factor de Expansión (FEX): Cada persona tiene un peso estadístico.
 *    NO usar COUNT(), usar SUM(FEX) para proyecciones nacionales.
 * 2. Categorías 7 y 8 (A15): Trabajadores en extranjero - EXCLUIR del análisis.
 * 3. Representatividad: Solo válido a nivel país, área urbana/rural y departamentos.
 */
return new class extends Migration
{
    public function up(): void
    {
        // ══════════════════════════════════════════════════════════════════════
        // DIMENSIÓN: Demografía DGEEC (Data Warehouse)
        // ══════════════════════════════════════════════════════════════════════
        Schema::connection('pgsql')->create('estadistica.dim_demografia_dgeec', function (Blueprint $table) {
            $table->id();
            
            // ── Dimensiones geográficas (FK a JSON de establecimientos) ───────
            $table->smallInteger('anio')->comment('Año de la encuesta EPHC');
            $table->tinyInteger('departamento_codigo')
                ->comment('Código DPTOREP: 0=Total País, 1-15=Departamentos');
            $table->enum('area', ['urbana', 'rural', 'total'])
                ->default('total')
                ->comment('AREA: 1=Urbana, 6=Rural, null=Total');
            
            // ── Población Económicamente Activa (Variables A02-A05) ───────────
            // Estas alimentan las tablas PP5, PP6, PP7, PP8 de la Resolución
            $table->decimal('poblacion_total', 15, 2)->default(0)
                ->comment('Sumatoria FEX - Población total proyectada');
            $table->decimal('pea_total', 15, 2)->default(0)
                ->comment('Sumatoria FEX donde A02=1 (PEA)');
            $table->decimal('pea_ocupada', 15, 2)->default(0)
                ->comment('Sumatoria FEX donde A03=1 (PEA Ocupada)');
            $table->decimal('pea_desocupada', 15, 2)->default(0)
                ->comment('Sumatoria FEX donde A04=1 (PEA Desocupada)');
            $table->decimal('pei', 15, 2)->default(0)
                ->comment('Sumatoria FEX donde A05=1 (PEI - Población Económicamente Inactiva)');
            
            // ── Informalidad Laboral (Variables B10, B11) ──────────────────────
            // CRÍTICO: Alimenta la tabla AP15 de la Resolución
            $table->decimal('aportantes_ips', 15, 2)->default(0)
                ->comment('Sumatoria FEX donde B11=1 (Aportan al IPS)');
            $table->decimal('aportantes_otras_cajas', 15, 2)->default(0)
                ->comment('Sumatoria FEX donde B11 IN (2,3,4,5) (Caja Fiscal, MSPBS, etc.)');
            $table->decimal('no_aportantes', 15, 2)->default(0)
                ->comment('Sumatoria FEX donde B10=6 (No aporta a ninguna caja)');
            $table->decimal('informalidad_total', 15, 2)->default(0)
                ->comment('Sumatoria FEX donde B10!=1 o B11!=1 (Informales/Evasores)');
            
            // ── Categoría Ocupacional (Variable A15) ───────────────────────────
            // Permite cruce con tabla TR2 (Cotizantes por tipo de seguro)
            $table->decimal('ocup_empleado_publico', 15, 2)->default(0)
                ->comment('Sumatoria FEX donde A15=1');
            $table->decimal('ocup_empleado_privado', 15, 2)->default(0)
                ->comment('Sumatoria FEX donde A15=2');
            $table->decimal('ocup_cuenta_propia', 15, 2)->default(0)
                ->comment('Sumatoria FEX donde A15=3');
            $table->decimal('ocup_empleador', 15, 2)->default(0)
                ->comment('Sumatoria FEX donde A15=4');
            $table->decimal('ocup_trabajador_familiar', 15, 2)->default(0)
                ->comment('Sumatoria FEX donde A15=5');
            $table->decimal('ocup_empleado_domestico', 15, 2)->default(0)
                ->comment('Sumatoria FEX donde A15=6');
            // NOTA: Categorías 7 y 8 (trabajadores en extranjero) se excluyen
            // según reglas de recodificación EPHC 2018+
            
            // ── Ingresos Laborales (Variables B16G, B16T) ─────────────────────
            // Para tabla TR4 (Salarios Mínimos Legales)
            $table->decimal('ingreso_promedio_publico', 15, 2)->nullable()
                ->comment('Promedio ponderado FEX de B16T donde A15=1');
            $table->decimal('ingreso_promedio_privado', 15, 2)->nullable()
                ->comment('Promedio ponderado FEX de B16T donde A15=2');
            $table->decimal('ingreso_promedio_cuenta_propia', 15, 2)->nullable()
                ->comment('Promedio ponderado FEX de B16T donde A15=3');
            $table->decimal('ingreso_mediana_nacional', 15, 2)->nullable()
                ->comment('Mediana de ingresos a nivel país');
            
            // ── Pobreza (Variable POBREZAI) ───────────────────────────────────
            // Para cruce con red de establecimientos (Tabla PP1)
            $table->decimal('poblacion_pobre', 15, 2)->default(0)
                ->comment('Sumatoria FEX donde POBREZAI=1 (Pobres)');
            $table->decimal('poblacion_pobre_extremo', 15, 2)->default(0)
                ->comment('Sumatoria FEX donde POBREZAI=2 (Pobres extremos)');
            
            // ── Pre-Jubilación (Variable A18) ─────────────────────────────────
            // Para estimar demanda de jubilaciones vs tabla JU1
            $table->decimal('jubilados_encuesta', 15, 2)->default(0)
                ->comment('Sumatoria FEX donde A18=8 (Se jubiló)');
            
            // ── Metadatos de la fuente ────────────────────────────────────────
            $table->string('fuente', 50)->default('EPHC-DGEEC');
            $table->string('periodo_referencia')->nullable()
                ->comment('Ej: "2024-III" (tercer trimestre 2024)');
            $table->text('notas_metodologicas')->nullable();
            
            // ── Traza ─────────────────────────────────────────────────────────
            $table->unsignedInteger('cargado_por')->nullable();
            $table->foreign('cargado_por')->references('id')->on('users')->nullOnDelete();
            $table->timestamps();
            
            // ── Índices y restricciones ───────────────────────────────────────
            $table->unique(['anio', 'departamento_codigo', 'area'], 'ux_demografia_dgeec');
            $table->index(['anio', 'departamento_codigo']);
        });
        
        // ── Vista para KPIs automáticos ───────────────────────────────────────
        DB::connection('pgsql')->statement("
            CREATE OR REPLACE VIEW estadistica.vw_kpi_penetracion_ips AS
            SELECT 
                anio,
                departamento_codigo,
                area,
                pea_ocupada,
                aportantes_ips,
                CASE 
                    WHEN pea_ocupada > 0 
                    THEN ROUND((aportantes_ips / pea_ocupada * 100)::numeric, 4)
                    ELSE 0 
                END as tasa_penetracion_ephc,
                informalidad_total,
                CASE 
                    WHEN pea_ocupada > 0 
                    THEN ROUND((informalidad_total / pea_ocupada * 100)::numeric, 4)
                    ELSE 0 
                END as tasa_informalidad,
                poblacion_pobre,
                poblacion_pobre_extremo
            FROM estadistica.dim_demografia_dgeec
            ORDER BY anio DESC, departamento_codigo, area
        ");
        
        // ── Sembrar datos de ejemplo (Total País 2024) ────────────────────────
        // Estos valores son ilustrativos - deben venir del procesamiento EPHC
        DB::connection('pgsql')->table('estadistica.dim_demografia_dgeec')->insert([
            'anio' => 2024,
            'departamento_codigo' => 0,
            'area' => 'total',
            'poblacion_total' => 7454000,
            'pea_total' => 3520000,
            'pea_ocupada' => 3310000,
            'pea_desocupada' => 210000,
            'pei' => 3934000,
            'aportantes_ips' => 1250000,
            'aportantes_otras_cajas' => 350000,
            'no_aportantes' => 1710000,
            'informalidad_total' => 2060000,
            'ocup_empleado_publico' => 280000,
            'ocup_empleado_privado' => 1450000,
            'ocup_cuenta_propia' => 1200000,
            'ocup_empleador' => 150000,
            'ocup_trabajador_familiar' => 180000,
            'ocup_empleado_domestico' => 50000,
            'ingreso_promedio_publico' => 5500000,
            'ingreso_promedio_privado' => 4200000,
            'ingreso_promedio_cuenta_propia' => 2800000,
            'ingreso_mediana_nacional' => 3200000,
            'poblacion_pobre' => 1580000,
            'poblacion_pobre_extremo' => 520000,
            'jubilados_encuesta' => 180000,
            'fuente' => 'EPHC-DGEEC',
            'periodo_referencia' => '2024-III',
            'notas_metodologicas' => 'Valores ilustrativos - pendiente importación de datos EPHC procesados',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        DB::connection('pgsql')->statement('DROP VIEW IF EXISTS estadistica.vw_kpi_penetracion_ips');
        Schema::connection('pgsql')->dropIfExists('estadistica.dim_demografia_dgeec');
    }
};
