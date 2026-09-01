<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('planificacion.relevamiento_proceso_responsables', function (Blueprint $table) {
            if (!Schema::hasColumn('planificacion.relevamiento_proceso_responsables', 'firma_digital')) {
                $table->text('firma_digital')->nullable();
            }
            if (!Schema::hasColumn('planificacion.relevamiento_proceso_responsables', 'firmado_at')) {
                $table->timestamp('firmado_at')->nullable();
            }
            if (!Schema::hasColumn('planificacion.relevamiento_proceso_responsables', 'observaciones_firma')) {
                $table->string('observaciones_firma')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('planificacion.relevamiento_proceso_responsables', function (Blueprint $table) {
            if (Schema::hasColumn('planificacion.relevamiento_proceso_responsables', 'firma_digital')) {
                $table->dropColumn(['firma_digital', 'firmado_at', 'observaciones_firma']);
            }
        });
    }
};
