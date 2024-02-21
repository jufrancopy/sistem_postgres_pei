<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Spatie\Permission\Contracts\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call([PermissionsTableSeeder::class]);
        // $this->call([RolesTableSeeder::class]);
        // $this->call([UsersTableSeeder::class]);
        // $this->call([OrganigramasTableSeeder::class]);
        // $this->call([GroupsTableSeeder::class]);
        $this->call([PatrimonySeeder::class]);
    }
}
