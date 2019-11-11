<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class OrganigramasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('globales.organigramas')->insert([
            'dependency' => 'Instituto de Previsión Social',
            'dependency_id'=> null,
            'responsable'=> 'El Presidente',
            'telefono'=> rand(10000, 20000),
            'email' => Str::random(10).'@ips.gov.py',
            'user_id' => 1,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('globales.organigramas')->insert([
            'dependency' => 'Consejo de Administracion',
            'dependency_id'=> 1,
            'responsable'=> 'Los Consejeros',
            'telefono'=> rand(10000, 20000),
            'email' => Str::random(10).'@ips.gov.py',
            'user_id' => 1,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('globales.organigramas')->insert([
            'dependency' => 'Gerencia de Desarrollo y Tecnología',
            'dependency_id'=> 2,
            'responsable'=> 'Lourdes Drews',
            'telefono'=> rand(10000, 20000),
            'email' => Str::random(10).'@ips.gov.py',
            'user_id' => 1,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('globales.organigramas')->insert([
            'dependency' => 'Dirección de Planificación',
            'dependency_id'=> 3,
            'responsable'=> 'Ing. Evelyn Alviso',
            'telefono'=> rand(10000, 20000),
            'email' => Str::random(10).'@ips.gov.py',
            'user_id' => 1,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('globales.organigramas')->insert([
            'dependency' => 'Departamento de Planificación',
            'dependency_id'=> 4,
            'responsable'=> 'Lic. Maureen Eisenhut',
            'telefono'=> rand(10000, 20000),
            'email' => Str::random(10).'@ips.gov.py',
            'user_id' => 1,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('globales.organigramas')->insert([
            'dependency' => 'Sección Monitoreo y Control',
            'dependency_id'=> 5,
            'responsable'=> 'César Acosta',
            'telefono'=> rand(10000, 20000),
            'email' => Str::random(10).'@ips.gov.py',
            'user_id' => 1,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('globales.organigramas')->insert([
            'dependency' => 'Sección Planificación',
            'dependency_id'=> 5,
            'responsable'=> 'Abog. Jorgelina Gómez de la Fuente',
            'telefono'=> rand(10000, 20000),
            'email' => Str::random(10).'@ips.gov.py',
            'user_id' => 1,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('globales.organigramas')->insert([
            'dependency' => 'Departamento de Estadísticas',
            'dependency_id'=> 4,
            'responsable'=> 'Aleli',
            'telefono'=> rand(10000, 20000),
            'email' => Str::random(10).'@ips.gov.py',
            'user_id' => 1,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('globales.organigramas')->insert([
            'dependency' => 'Sección Estadísticas de la Seguridad Social',
            'dependency_id'=> 8,
            'responsable'=> 'Arq. Cristina Cabrera',
            'telefono'=> rand(10000, 20000),
            'email' => Str::random(10).'@ips.gov.py',
            'user_id' => 1,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('globales.organigramas')->insert([
            'dependency' => 'Sección Bio-Estadísticas ',
            'dependency_id'=> 8,
            'responsable'=> 'Lic.Cristhian Gómez',
            'telefono'=> rand(10000, 20000),
            'email' => Str::random(10).'@ips.gov.py',
            'user_id' => 1,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

    }
}
