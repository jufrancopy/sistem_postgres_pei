<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;

use Spatie\Permission\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->insert([
            'name' => 'role-list',
            'guard_name'=> 'web',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('permissions')->insert([
            'name' => 'role-edit',
            'guard_name'=> 'web',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('permissions')->insert([
            'name' => 'role-create',
            'guard_name'=> 'web',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('permissions')->insert([
            'name' => 'role-delete',
            'guard_name'=> 'web',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
    }
}
