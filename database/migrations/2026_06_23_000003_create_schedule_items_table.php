<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('schedule_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('schedule_id')->constrained('schedules')->onDelete('cascade');
            $table->foreignId('activity_id')->nullable()->constrained('activities')->nullOnDelete();
            $table->foreignId('activity_task_id')->nullable()->constrained('activity_tasks')->nullOnDelete();
            $table->foreignId('responsible_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('status')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('schedule_items');
    }
};
