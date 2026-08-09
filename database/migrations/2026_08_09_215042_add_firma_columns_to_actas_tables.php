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
        Schema::table('activity_task_actas', function (Blueprint $table) {
            $table->string('hash_seguridad', 128)->nullable()->after('estado');
            $table->longText('firma_moderador')->nullable()->after('hash_seguridad');
            $table->timestamp('fecha_firma_moderador')->nullable()->after('firma_moderador');
            $table->string('qr_seguridad_path')->nullable()->after('fecha_firma_moderador');
        });

        Schema::table('activity_task_acta_participantes', function (Blueprint $table) {
            $table->longText('firma')->nullable()->after('telefono');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activity_task_acta_participantes', function (Blueprint $table) {
            $table->dropColumn(['firma']);
        });

        Schema::table('activity_task_actas', function (Blueprint $table) {
            $table->dropColumn(['hash_seguridad', 'firma_moderador', 'fecha_firma_moderador', 'qr_seguridad_path']);
        });
    }
};
