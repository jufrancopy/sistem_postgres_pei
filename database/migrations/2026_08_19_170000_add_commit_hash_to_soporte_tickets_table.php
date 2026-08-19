<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('soporte_tickets', function (Blueprint $table) {
            $table->string('commit_hash', 100)->nullable()->after('respuesta_admin');
        });
    }

    public function down(): void
    {
        Schema::table('soporte_tickets', function (Blueprint $table) {
            $table->dropColumn('commit_hash');
        });
    }
};
