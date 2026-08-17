<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('pgsql')->create('bioestadistica.reportes', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 80);
            $table->string('nombre', 250);
            $table->text('descripcion')->nullable();
            $table->foreignId('formulario_id')
                ->nullable()
                ->constrained('bioestadistica.formularios')
                ->nullOnDelete();
            $table->jsonb('definicion');
            $table->boolean('publico')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        DB::connection('pgsql')->statement(
            'CREATE UNIQUE INDEX bio_reportes_codigo_unique
             ON bioestadistica.reportes (codigo)
             WHERE deleted_at IS NULL'
        );
        DB::connection('pgsql')->statement(
            'CREATE INDEX bio_reportes_definicion_gin ON bioestadistica.reportes USING gin (definicion)'
        );

        Schema::connection('pgsql')->create('bioestadistica.dashboards', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 80);
            $table->string('nombre', 250);
            $table->text('descripcion')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->boolean('es_default')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
        DB::connection('pgsql')->statement(
            'CREATE UNIQUE INDEX bio_dashboards_codigo_user_unique
             ON bioestadistica.dashboards (codigo, COALESCE(user_id, 0))
             WHERE deleted_at IS NULL'
        );

        Schema::connection('pgsql')->create('bioestadistica.dashboard_widgets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dashboard_id')
                ->constrained('bioestadistica.dashboards')
                ->cascadeOnDelete();
            $table->string('tipo', 30);
            $table->string('titulo', 250);
            $table->jsonb('query_config');
            $table->integer('pos_x')->default(0);
            $table->integer('pos_y')->default(0);
            $table->integer('ancho')->default(4);
            $table->integer('alto')->default(3);
            $table->timestamps();
            $table->index('dashboard_id');
        });
        DB::connection('pgsql')->statement(
            "ALTER TABLE bioestadistica.dashboard_widgets
             ADD CONSTRAINT bio_dashboard_widgets_tipo_check
             CHECK (tipo IN ('kpi','tabla','barras','lineas','pastel','heatmap','indicador'))"
        );
        DB::connection('pgsql')->statement(
            'ALTER TABLE bioestadistica.dashboard_widgets
             ADD CONSTRAINT bio_dashboard_widgets_grid_check
             CHECK (ancho BETWEEN 1 AND 12 AND alto BETWEEN 1 AND 12
                    AND pos_x BETWEEN 0 AND 11 AND pos_y >= 0)'
        );
        DB::connection('pgsql')->statement(
            'CREATE INDEX bio_dashboard_widgets_query_gin
             ON bioestadistica.dashboard_widgets USING gin (query_config)'
        );
    }

    public function down(): void
    {
        Schema::connection('pgsql')->dropIfExists('bioestadistica.dashboard_widgets');
        Schema::connection('pgsql')->dropIfExists('bioestadistica.dashboards');
        Schema::connection('pgsql')->dropIfExists('bioestadistica.reportes');
    }
};
