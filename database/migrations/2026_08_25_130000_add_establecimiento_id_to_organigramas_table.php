<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('organigramas') && !Schema::hasColumn('organigramas', 'establecimiento_id')) {
            Schema::table('organigramas', function (Blueprint $table) {
                $table->string('establecimiento_id')->nullable()->after('user_id');
                $table->index('establecimiento_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('organigramas') && Schema::hasColumn('organigramas', 'establecimiento_id')) {
            Schema::table('organigramas', function (Blueprint $table) {
                $table->dropColumn('establecimiento_id');
            });
        }
    }
};
