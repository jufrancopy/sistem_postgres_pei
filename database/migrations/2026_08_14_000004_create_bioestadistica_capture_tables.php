<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('pgsql')->create('bioestadistica.usuario_establecimientos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreignId('establecimiento_id')
                ->constrained('bioestadistica.establecimientos')
                ->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['user_id', 'establecimiento_id'], 'bio_usuario_establecimiento_unique');
            $table->index('user_id');
        });

        Schema::connection('pgsql')->create('bioestadistica.records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('formulario_id')
                ->constrained('bioestadistica.formularios')
                ->restrictOnDelete();
            $table->foreignId('establecimiento_id')
                ->constrained('bioestadistica.establecimientos')
                ->restrictOnDelete();
            $table->smallInteger('periodo_anio');
            $table->smallInteger('periodo_mes');
            $table->enum('estado', ['borrador', 'enviado', 'aprobado', 'objetado'])->default('borrador');
            $table->text('observacion')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('submitted_by')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->unique(
                ['formulario_id', 'establecimiento_id', 'periodo_anio', 'periodo_mes'],
                'bio_record_periodo_unique'
            );
            $table->index(['periodo_anio', 'periodo_mes']);
            $table->index(['establecimiento_id', 'periodo_anio', 'periodo_mes']);
            $table->index('estado');
        });
        DB::connection('pgsql')->statement(
            'ALTER TABLE bioestadistica.records ADD CONSTRAINT bio_records_mes_check CHECK (periodo_mes BETWEEN 1 AND 12)'
        );
        DB::connection('pgsql')->statement(
            'ALTER TABLE bioestadistica.records ADD CONSTRAINT bio_records_anio_check CHECK (periodo_anio BETWEEN 1990 AND 2100)'
        );

        Schema::connection('pgsql')->create('bioestadistica.record_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('record_id')
                ->constrained('bioestadistica.records')
                ->cascadeOnDelete();
            $table->foreignId('field_id')
                ->constrained('bioestadistica.fields')
                ->cascadeOnDelete();
            $table->text('value_text')->nullable();
            $table->decimal('value_num', 18, 4)->nullable();
            $table->date('value_date')->nullable();
            $table->boolean('value_bool')->nullable();
            $table->jsonb('value_json')->nullable();
            $table->timestamps();
            $table->unique(['record_id', 'field_id'], 'bio_record_value_unique');
            $table->index('field_id');
            $table->index(['field_id', 'value_num']);
        });
    }

    public function down(): void
    {
        Schema::connection('pgsql')->dropIfExists('bioestadistica.record_values');
        Schema::connection('pgsql')->dropIfExists('bioestadistica.records');
        Schema::connection('pgsql')->dropIfExists('bioestadistica.usuario_establecimientos');
    }
};
