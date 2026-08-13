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
        if (Schema::hasTable('plan_acciones') && !Schema::hasColumn('plan_acciones', 'pei_profile_id')) {
            Schema::table('plan_acciones', function (Blueprint $table) {
                $table->uuid('pei_profile_id')->nullable()->index();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('plan_acciones') && Schema::hasColumn('plan_acciones', 'pei_profile_id')) {
            Schema::table('plan_acciones', function (Blueprint $table) {
                $table->dropColumn('pei_profile_id');
            });
        }
    }
};
