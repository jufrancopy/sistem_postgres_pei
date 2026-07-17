<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('activity_tasks', function (Blueprint $table) {
            $table->uuid('pei_action_id')->nullable()->after('activity_id');
            $table->foreign('pei_action_id')
                  ->references('id')->on('planificacion.pei_profiles')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('activity_tasks', function (Blueprint $table) {
            $table->dropForeign(['pei_action_id']);
            $table->dropColumn('pei_action_id');
        });
    }
};
