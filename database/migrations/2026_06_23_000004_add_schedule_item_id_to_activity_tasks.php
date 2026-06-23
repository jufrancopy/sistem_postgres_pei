<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('activity_tasks', function (Blueprint $table) {
            $table->foreignId('schedule_item_id')->nullable()->after('completed_by')->constrained('schedule_items')->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('activity_tasks', function (Blueprint $table) {
            $table->dropForeign(['schedule_item_id']);
            $table->dropColumn('schedule_item_id');
        });
    }
};
