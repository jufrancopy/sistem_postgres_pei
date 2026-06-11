<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('activity_tasks', function (Blueprint $table) {
            $table->string('etiqueta', 80)->nullable()->after('details');
            $table->string('color', 7)->default('#6b7280')->after('etiqueta')
                  ->comment('Color hex de la etiqueta, ej: #e91e63');
        });
    }

    public function down(): void
    {
        Schema::table('activity_tasks', function (Blueprint $table) {
            $table->dropColumn(['etiqueta', 'color']);
        });
    }
};
