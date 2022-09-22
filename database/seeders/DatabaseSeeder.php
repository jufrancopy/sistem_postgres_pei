<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PermissionsTableSeeder::class);
        $this->call(RolesTableSeeder::class);    
        $this->call(UsersTableSeeder::class);
        $this->call(OrganigramasTableSeeder::class);
        $this->call(EquipamientosTableSeeder::class);
        // $this->call(EspecialidadTableSeeder::class);
        $this->call(TalentoHumanoTableSeeder::class);
        $this->call(InfraestructuraTableSeeder::class);
        $this->call(OtroServicioTableSeeder::class);
        $this->call(FodaModeloTableSeeder::class);
        $this->call(FodaCategoriaTableSeeder::class);
        $this->call(FodaAspectoTableSeeder::class);
        $this->call(FodaPerfilTableSeeder::class);
        // $this->call(PeiTableSeeder::class);
    }
}
