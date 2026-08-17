<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Admin\Planificacion\Pei\PeiProfile;

$root = PeiProfile::find('ce99f883-fdd0-4723-8f75-cf689aa8f0fa');
echo "=== PROBAR ACCESOR Y ENCODING EN PEI CHILDREN ===\n";
foreach ($root->children as $c) {
    echo "ID: {$c->id} | Name: " . strip_tags($c->name) . "\n";
    echo "  Raw parameters: " . var_export($c->parameters, true) . "\n";
    echo "  riesgos_mecip count: " . count($c->riesgos_mecip) . "\n";
    echo "  json_encode riesgos_mecip: " . json_encode($c->riesgos_mecip) . "\n\n";
}
