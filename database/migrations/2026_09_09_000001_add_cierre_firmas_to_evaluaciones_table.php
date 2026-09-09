<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('evaluaciones')) {
            Schema::table('evaluaciones', function (Blueprint $table) {
                if (!Schema::hasColumn('evaluaciones', 'responsable_nombre')) {
                    $table->string('responsable_nombre')->nullable()->after('observaciones_generales');
                }
                if (!Schema::hasColumn('evaluaciones', 'responsable_cargo')) {
                    $table->string('responsable_cargo')->nullable()->after('responsable_nombre');
                }
                if (!Schema::hasColumn('evaluaciones', 'responsable_documento')) {
                    $table->string('responsable_documento')->nullable()->after('responsable_cargo');
                }
                if (!Schema::hasColumn('evaluaciones', 'responsable_telefono')) {
                    $table->string('responsable_telefono')->nullable()->after('responsable_documento');
                }
                if (!Schema::hasColumn('evaluaciones', 'responsable_firma')) {
                    $table->longText('responsable_firma')->nullable()->after('responsable_telefono');
                }
                if (!Schema::hasColumn('evaluaciones', 'responsable_firmado_at')) {
                    $table->timestamp('responsable_firmado_at')->nullable()->after('responsable_firma');
                }
                if (!Schema::hasColumn('evaluaciones', 'firmas_evaluadores')) {
                    $table->json('firmas_evaluadores')->nullable()->after('responsable_firmado_at');
                }
                if (!Schema::hasColumn('evaluaciones', 'cierre_observaciones')) {
                    $table->text('cierre_observaciones')->nullable()->after('firmas_evaluadores');
                }
                if (!Schema::hasColumn('evaluaciones', 'cerrado_at')) {
                    $table->timestamp('cerrado_at')->nullable()->after('cierre_observaciones');
                }
                if (!Schema::hasColumn('evaluaciones', 'cerrado_por_id')) {
                    $table->foreignId('cerrado_por_id')->nullable()->after('cerrado_at')->constrained('users')->nullOnDelete();
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('evaluaciones')) {
            Schema::table('evaluaciones', function (Blueprint $table) {
                $columns = [
                    'responsable_nombre',
                    'responsable_cargo',
                    'responsable_documento',
                    'responsable_telefono',
                    'responsable_firma',
                    'responsable_firmado_at',
                    'firmas_evaluadores',
                    'cierre_observaciones',
                    'cerrado_at',
                    'cerrado_por_id',
                ];
                foreach ($columns as $col) {
                    if (Schema::hasColumn('evaluaciones', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }
    }
};
