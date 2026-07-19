<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pei_profile_tasks', function (Blueprint $table) {
            $table->id();
            $table->uuid('pei_profile_id');
            $table->unsignedBigInteger('activity_task_id');
            $table->timestamps();

            $table->foreign('pei_profile_id')
                  ->references('id')->on('planificacion.pei_profiles')
                  ->onDelete('cascade');

            $table->foreign('activity_task_id')
                  ->references('id')->on('activity_tasks')
                  ->onDelete('cascade');

            $table->unique(['pei_profile_id', 'activity_task_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pei_profile_tasks');
    }
};
