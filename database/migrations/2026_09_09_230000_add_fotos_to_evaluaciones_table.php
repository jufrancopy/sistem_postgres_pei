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
        if (Schema::hasTable('evaluaciones') && !Schema::hasColumn('evaluaciones', 'fotos')) {
            Schema::table('evaluaciones', function (Blueprint $table) {
                $table->json('fotos')->nullable()->after('aspectos_positivos');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('evaluaciones') && Schema::hasColumn('evaluaciones', 'fotos')) {
            Schema::table('evaluaciones', function (Blueprint $table) {
                $table->dropColumn('fotos');
            });
        }
    }
};
