<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->uuid('pei_profile_id')->nullable()->after('date_end');
            $table->foreign('pei_profile_id')
                  ->references('id')->on('planificacion.pei_profiles')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->dropForeign(['pei_profile_id']);
            $table->dropColumn('pei_profile_id');
        });
    }
};
