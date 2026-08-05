<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('activity_tasks', function (Blueprint $table) {
            $table->boolean('es_documento')->default(false)->after('es_reunion');
        });
    }

    public function down(): void
    {
        Schema::table('activity_tasks', function (Blueprint $table) {
            $table->dropColumn('es_documento');
        });
    }
};
