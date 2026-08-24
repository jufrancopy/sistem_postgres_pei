<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_reunion_photos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('activity_task_id');
            $table->foreign('activity_task_id')->references('id')->on('activity_tasks')->cascadeOnDelete();
            $table->string('filename');        // path WebP full en storage/public
            $table->string('thumb_path');      // path WebP miniatura en storage/public
            $table->string('original_name');   // nombre original subido
            $table->unsignedInteger('size_bytes')->default(0); // tamaño WebP resultante
            $table->unsignedBigInteger('uploaded_by')->nullable();
            $table->foreign('uploaded_by')->references('id')->on('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_reunion_photos');
    }
};
