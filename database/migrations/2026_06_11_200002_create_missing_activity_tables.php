<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // activity_tasks
        if (!Schema::hasTable('activity_tasks')) {
            Schema::create('activity_tasks', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('activity_id');
                $table->foreign('activity_id')->references('id')->on('activities')->cascadeOnDelete();
                $table->string('title');
                $table->text('details')->nullable();
                $table->string('etiqueta', 80)->nullable();
                $table->string('color', 7)->default('#6b7280');
                $table->unsignedBigInteger('assigned_to')->nullable();
                $table->foreign('assigned_to')->references('id')->on('users')->nullOnDelete();
                $table->tinyInteger('status')->default(0)->comment('0=pendiente,1=progreso,2=hecho,3=revision,4=priorizado');
                $table->timestamp('completed_at')->nullable();
                $table->unsignedBigInteger('completed_by')->nullable();
                $table->foreign('completed_by')->references('id')->on('users')->nullOnDelete();
                $table->text('completion_note')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        } else {
            // Si existe pero le faltan columnas
            Schema::table('activity_tasks', function (Blueprint $table) {
                if (!Schema::hasColumn('activity_tasks', 'etiqueta')) {
                    $table->string('etiqueta', 80)->nullable()->after('details');
                }
                if (!Schema::hasColumn('activity_tasks', 'color')) {
                    $table->string('color', 7)->default('#6b7280')->after('etiqueta');
                }
            });
        }

        // activity_task_evidences
        if (!Schema::hasTable('activity_task_evidences')) {
            Schema::create('activity_task_evidences', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('activity_task_id');
                $table->foreign('activity_task_id')->references('id')->on('activity_tasks')->cascadeOnDelete();
                $table->string('type', 20)->comment('url, image, document');
                $table->string('label');
                $table->text('value');
                $table->unsignedBigInteger('user_id')->nullable();
                $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
                $table->timestamps();
            });
        }

        // activities_has_responsibles
        if (!Schema::hasTable('activities_has_responsibles')) {
            Schema::create('activities_has_responsibles', function (Blueprint $table) {
                $table->unsignedBigInteger('activity_id');
                $table->unsignedBigInteger('responsible_id');
                $table->foreign('activity_id')->references('id')->on('activities')->cascadeOnDelete();
                $table->foreign('responsible_id')->references('id')->on('users')->cascadeOnDelete();
                $table->primary(['activity_id', 'responsible_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_task_evidences');
        Schema::dropIfExists('activity_tasks');
        Schema::dropIfExists('activities_has_responsibles');
    }
};
