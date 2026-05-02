<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ══════════════════════════════════════════════════════════════════════
        // MÓDULO 2 — PL: Poblacional
        // ══════════════════════════════════════════════════════════════════════
        Schema::connection('pgsql')->create('estadistica.pl_estructura', function (Blueprint $table) {
            $table->id();
            $table->foreignId('extracto_id')->constrained('estadistica.siess_extractos')->cascadeOnDelete();
            $table->foreignId('periodo_id')->constrained('estadistica.siess_periodos');
            $table->bigInteger('pea')->nullable()->comment('Población Económicamente Activa');
            $table->bigInteger('peao')->nullable()->comment('PEA Ocupada');
            $table->bigInteger('peaoo_meta')->nullable()->comment('PEAOO Meta');
            $table->bigInteger('pli')->nullable()->comment('Población con seguro IPS');
            $table->decimal('pct_cobertura_peaoo', 8, 4)->nullable();
            $table->decimal('pct_poblacion_pais', 8, 4)->nullable();
            $table->decimal('tasa_incremento_asegurados', 8, 4)->nullable();
            $table->decimal('tasa_cotizante_beneficiario', 8, 4)->nullable();
            $table->timestamps();
        });

        Schema::connection('pgsql')->create('estadistica.pl_historico', function (Blueprint $table) {
            $table->id();
            $table->foreignId('extracto_id')->constrained('estadistica.siess_extractos')->cascadeOnDelete();
            $table->foreignId('periodo_id')->constrained('estadistica.siess_periodos');
            $table->integer('anio_referencia');
            $table->bigInteger('total_asegurados')->default(0);
            $table->bigInteger('cotizantes')->default(0);
            $table->bigInteger('beneficiarios')->default(0);
            $table->decimal('tasa_incremento_asegurados', 8, 4)->nullable();
            $table->decimal('tasa_incremento_cotizantes', 8, 4)->nullable();
            $table->timestamps();
        });

        // ══════════════════════════════════════════════════════════════════════
        // MÓDULO 4 — RL: Subsidios y Riesgo Laboral
        // ══════════════════════════════════════════════════════════════════════
        Schema::connection('pgsql')->create('estadistica.rl_subsidios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('extracto_id')->constrained('estadistica.siess_extractos')->cascadeOnDelete();
            $table->foreignId('periodo_id')->constrained('estadistica.siess_periodos');
            $table->string('nro_certificado', 30)->nullable();
            $table->date('fecha_registro')->nullable();
            $table->date('fecha_verificacion')->nullable();
            $table->date('fecha_liquidacion')->nullable();
            $table->date('fecha_pago')->nullable();
            $table->string('diagnostico')->nullable();
            $table->integer('dias_reposo')->default(0);
            $table->enum('tipo_reposo', ['primer_reposo', 'extension'])->nullable();
            $table->boolean('es_covid')->default(false);
            $table->string('medio_pago')->nullable();
            $table->decimal('monto', 18, 2)->default(0);
            $table->string('empleador_ruc', 20)->nullable();
            $table->string('empleador_descripcion')->nullable();
            $table->string('actividad')->nullable();
            $table->timestamps();
            $table->index(['periodo_id', 'es_covid']);
            $table->index(['periodo_id', 'tipo_reposo']);
        });

        // ══════════════════════════════════════════════════════════════════════
        // MÓDULO 5 — DI: Inversiones
        // ══════════════════════════════════════════════════════════════════════
        Schema::connection('pgsql')->create('estadistica.di_portafolio', function (Blueprint $table) {
            $table->id();
            $table->foreignId('extracto_id')->constrained('estadistica.siess_extractos')->cascadeOnDelete();
            $table->foreignId('periodo_id')->constrained('estadistica.siess_periodos');
            $table->string('instrumento');
            $table->enum('modalidad', ['COA', 'Bonos', 'Titulos', 'Prestamos', 'Fianzas', 'CertificadoDeposito', 'Otros']);
            $table->decimal('monto', 18, 2)->default(0);
            $table->string('divisa', 5)->default('PYG');
            $table->decimal('tasa', 8, 4)->nullable();
            $table->integer('plazo_dias')->nullable();
            $table->decimal('rentabilidad', 8, 4)->nullable();
            $table->date('fecha_vencimiento')->nullable();
            $table->timestamps();
            $table->index(['periodo_id', 'modalidad']);
        });

        Schema::connection('pgsql')->create('estadistica.di_prestamos_caja', function (Blueprint $table) {
            $table->id();
            $table->foreignId('extracto_id')->constrained('estadistica.siess_extractos')->cascadeOnDelete();
            $table->foreignId('periodo_id')->constrained('estadistica.siess_periodos');
            $table->enum('tipo_cliente', ['jubilado', 'funcionario']);
            $table->decimal('monto_total', 18, 2)->default(0);
            $table->decimal('cuota_promedio', 18, 2)->nullable();
            $table->decimal('monto_morosidad', 18, 2)->default(0);
            $table->integer('cantidad_prestamos')->default(0);
            $table->timestamps();
        });

        // ══════════════════════════════════════════════════════════════════════
        // MÓDULO 7 — RH: Recursos Humanos
        // ══════════════════════════════════════════════════════════════════════
        Schema::connection('pgsql')->create('estadistica.rh_nomina', function (Blueprint $table) {
            $table->id();
            $table->foreignId('extracto_id')->constrained('estadistica.siess_extractos')->cascadeOnDelete();
            $table->foreignId('periodo_id')->constrained('estadistica.siess_periodos');
            $table->string('cedula', 20)->nullable();
            $table->string('cargo')->nullable();
            $table->string('grupo_ocupacional')->nullable();
            $table->string('dependencia')->nullable();
            $table->decimal('carga_horaria', 5, 2)->nullable();
            $table->decimal('remuneracion_presupuestada', 18, 2)->default(0);
            $table->decimal('remuneracion_devengada', 18, 2)->default(0);
            $table->boolean('tiene_discapacidad')->default(false);
            $table->enum('sexo', ['M', 'F', 'otro'])->nullable();
            $table->timestamps();
            $table->index(['periodo_id', 'grupo_ocupacional']);
        });

        Schema::connection('pgsql')->create('estadistica.rh_movimientos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('extracto_id')->constrained('estadistica.siess_extractos')->cascadeOnDelete();
            $table->foreignId('periodo_id')->constrained('estadistica.siess_periodos');
            $table->enum('tipo', ['comisionamiento', 'capacitacion', 'alta', 'baja', 'ausentismo_grave', 'ausentismo_leve', 'evaluacion_desempeno']);
            $table->string('descripcion')->nullable();
            $table->integer('cantidad')->default(0);
            $table->decimal('valor_adicional', 18, 2)->nullable()->comment('Costo capacitación, días ausentismo, etc.');
            $table->timestamps();
        });

        // ══════════════════════════════════════════════════════════════════════
        // MÓDULO 8 — INF: Infraestructura y Servicios Generales
        // ══════════════════════════════════════════════════════════════════════
        Schema::connection('pgsql')->create('estadistica.inf_proyectos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('extracto_id')->constrained('estadistica.siess_extractos')->cascadeOnDelete();
            $table->foreignId('periodo_id')->constrained('estadistica.siess_periodos');
            $table->string('nombre');
            $table->string('ubicacion')->nullable();
            $table->decimal('monto_obra', 18, 2)->default(0);
            $table->decimal('avance_pct', 5, 2)->default(0);
            $table->boolean('tiene_licencia_ambiental')->default(false);
            $table->enum('estado', ['planificado', 'en_ejecucion', 'paralizado', 'finalizado'])->default('planificado');
            $table->timestamps();
        });

        Schema::connection('pgsql')->create('estadistica.ga_servicios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('extracto_id')->constrained('estadistica.siess_extractos')->cascadeOnDelete();
            $table->foreignId('periodo_id')->constrained('estadistica.siess_periodos');
            $table->enum('tipo', ['mantenimiento', 'vehiculos', 'combustible', 'limpieza', 'residuos_patologicos', 'fumigacion', 'contrataciones', 'sumarios', 'amparos', 'otros']);
            $table->string('descripcion')->nullable();
            $table->decimal('monto', 18, 2)->default(0);
            $table->integer('cantidad')->nullable();
            $table->string('unidad_medida')->nullable();
            $table->timestamps();
        });

        // ══════════════════════════════════════════════════════════════════════
        // MÓDULO 9 — CAU: Salud y Atención al Usuario
        // ══════════════════════════════════════════════════════════════════════
        Schema::connection('pgsql')->create('estadistica.sal_suministros', function (Blueprint $table) {
            $table->id();
            $table->foreignId('extracto_id')->constrained('estadistica.siess_extractos')->cascadeOnDelete();
            $table->foreignId('periodo_id')->constrained('estadistica.siess_periodos');
            $table->string('item_vademecum')->nullable();
            $table->string('contrato')->nullable();
            $table->enum('tipo', ['medicamento', 'gas_medicinal', 'insumo']);
            $table->decimal('cantidad_producida', 18, 2)->default(0);
            $table->decimal('cantidad_entregada', 18, 2)->default(0);
            $table->string('unidad_medida')->nullable();
            $table->timestamps();
        });

        Schema::connection('pgsql')->create('estadistica.cau_atencion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('extracto_id')->constrained('estadistica.siess_extractos')->cascadeOnDelete();
            $table->foreignId('periodo_id')->constrained('estadistica.siess_periodos');
            $table->enum('canal', ['voz', 'chat', 'email', 'presencial', 'otros']);
            $table->integer('total_contactos')->default(0);
            $table->decimal('tiempo_espera_promedio_seg', 10, 2)->nullable();
            $table->decimal('tiempo_espera_max_seg', 10, 2)->nullable();
            $table->integer('transferencias')->default(0);
            $table->integer('abandonos')->default(0);
            $table->decimal('tasa_abandono', 8, 4)
                ->storedAs('CASE WHEN total_contactos > 0 THEN ROUND((abandonos::numeric / total_contactos * 100), 4) ELSE 0 END');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        $tablas = [
            'estadistica.cau_atencion',
            'estadistica.sal_suministros',
            'estadistica.ga_servicios',
            'estadistica.inf_proyectos',
            'estadistica.rh_movimientos',
            'estadistica.rh_nomina',
            'estadistica.di_prestamos_caja',
            'estadistica.di_portafolio',
            'estadistica.rl_subsidios',
            'estadistica.pl_historico',
            'estadistica.pl_estructura',
        ];
        foreach ($tablas as $tabla) {
            Schema::connection('pgsql')->dropIfExists($tabla);
        }
    }
};
