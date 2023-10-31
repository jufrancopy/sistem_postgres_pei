<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Kalnoy\Nestedset\NestedSet;

class CreatePeiProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('planificacion.pei_profiles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->text('name');
            $table->date('year_start')->nullable();
            $table->date('year_end')->nullable();
            $table->string('type')->nullable();
            $table->string('level')->nullable();
            $table->text('mision')->nullable();
            $table->text('vision')->nullable();
            $table->text('values')->nullable();
            $table->string('period')->nullable();
            $table->decimal('numerator')->nullable();
            $table->string('operator')->nullable();
            $table->decimal('denominator')->nullable();
            $table->decimal('goal')->nullable();
            $table->decimal('progress')->nullable();

            //Nuevos Inputs que no se incluyeron en mi mgiracion primaria
            $table->text('action')->nullable();
            $table->text('indicator')->nullable();
            $table->text('baseline')->nullable();
            $table->text('target')->nullable();

            //En las correcciones finales group_id y dependency_id se volvieron nullable()
            $table->unsignedInteger('group_id')->nullable();
            $table->foreign('group_id')->references('id')->on('groups')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->unsignedInteger('dependency_id')->nullable();
            $table->foreign('dependency_id')->references('id')->on('organigramas')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->nestedSet();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('planificacion.pei_profiles', function (Blueprint $table) {
            //
        });
    }
}
