<?php

namespace Tests\Feature;

use App\Admin\Globales\Organigrama;
use Database\Seeders\OrganigramaIpsSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class OrganigramaIpsSeederTest extends TestCase
{
    use DatabaseTransactions;

    public function test_it_reuses_the_existing_ips_root_and_skips_duplicates(): void
    {
        $root = Organigrama::create([
            'dependency' => 'INSTITUTO DE PREVISIÓN SOCIAL',
            'manager'    => 'Director',
            'email'      => 'root@ips.test',
            'phone'      => '000000',
        ]);

        $root->appendNode(new Organigrama([
            'dependency' => 'CONSEJO DE ADMINISTRACIÓN',
            'manager'    => 'Presidente',
            'email'      => 'consejo@ips.test',
            'phone'      => '000000',
        ]));

        $this->artisan('db:seed', ['--class' => OrganigramaIpsSeeder::class])
            ->assertSuccessful();

        $this->assertSame(1, Organigrama::whereNull('parent_id')
            ->where('dependency', 'INSTITUTO DE PREVISIÓN SOCIAL')
            ->count());

        $this->assertSame(1, Organigrama::where('dependency', 'CONSEJO DE ADMINISTRACIÓN')
            ->where('parent_id', $root->id)
            ->count());
    }
}
