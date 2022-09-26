<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Str;

class OrganigramasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('organigramas')->insert([
            'dependency' => 'Gerencia de Salud',
            'parent_id' => null,
            'manager' => 'El Gerente',
            'phone' => rand(10000, 20000),
            'email' => Str::random(10) . '@ips.gov.py',
            'user_id' => 1,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('organigramas')->insert([
            'dependency' => 'Dirección de Hospitales Área Central',
            'parent_id' => 1,
            'manager' => 'El Director',
            'phone' => rand(10000, 20000),
            'email' => Str::random(10) . '@ips.gov.py',
            'user_id' => 1,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('organigramas')->insert([
            'dependency' => 'Clinica Pereférica Yrendague',
            'parent_id' => 2,
            'manager' => 'El Director',
            'phone' => rand(10000, 20000),
            'email' => Str::random(10) . '@ips.gov.py',
            'user_id' => 1,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('organigramas')->insert([
            'dependency' => 'Clinica Pereférica Isla Poí',
            'parent_id' => 2,
            'manager' => 'Dr César Acosta',
            'phone' => rand(10000, 20000),
            'email' => Str::random(10) . '@ips.gov.py',
            'user_id' => 1,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('organigramas')->insert([
            'dependency' => 'Clinica Pereférica Nanawa',
            'parent_id' => 2,
            'manager' => 'Dr Estean Duarte',
            'phone' => rand(10000, 20000),
            'email' => Str::random(10) . '@ips.gov.py',
            'user_id' => 1,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
    }
}
