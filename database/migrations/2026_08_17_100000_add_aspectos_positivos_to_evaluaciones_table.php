<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('evaluaciones') && !Schema::hasColumn('evaluaciones', 'aspectos_positivos')) {
            Schema::table('evaluaciones', function (Blueprint $table) {
                $table->text('aspectos_positivos')->nullable()->after('observaciones_generales');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('evaluaciones') && Schema::hasColumn('evaluaciones', 'aspectos_positivos')) {
            Schema::table('evaluaciones', function (Blueprint $table) {
                $table->dropColumn('aspectos_positivos');
            });
        }
    }
};
