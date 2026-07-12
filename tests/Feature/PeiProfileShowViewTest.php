<?php

namespace Tests\Feature;

use Tests\TestCase;

class PeiProfileShowViewTest extends TestCase
{
    public function test_show_view_uses_the_correct_local_storage_variable(): void
    {
        $path = resource_path('views/admin/planificacion/peis/peis/show.blade.php');
        $contents = file_get_contents($path);

        $this->assertStringContainsString("var storedType = localStorage.getItem('type');", $contents);
        $this->assertStringNotContainsString('storageType', $contents);
    }
}
