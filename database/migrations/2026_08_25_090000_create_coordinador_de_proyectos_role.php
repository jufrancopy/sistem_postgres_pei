<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Role;

class CreateCoordinadorDeProyectosRole extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Role::firstOrCreate(['name' => 'Coordinador de Proyectos']);
        Role::firstOrCreate(['name' => 'Coordinación de Proyectos']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // No revertir roles
    }
}
