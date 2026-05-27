<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('evaluaciones', function (Blueprint $table) {
            $table->json('evaluadores')->nullable()->after('evaluador_nombre')
                  ->comment('Array de {id, name, email} de los evaluadores');
        });
    }

    public function down(): void
    {
        Schema::table('evaluaciones', function (Blueprint $table) {
            $table->dropColumn('evaluadores');
        });
    }
};
