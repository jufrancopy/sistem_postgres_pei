<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$field = App\Models\Bioestadistica\Formulario::where('codigo', 'SP9')
    ->with(['secciones.fields.detalle.prestaciones'])
    ->first()
    ?->secciones->flatMap->fields
    ->firstWhere('code', 'var_4_atencion_de_urgencias_adultos');

foreach ($field->detalle->prestaciones as $p) {
    echo $p->id . ' | ' . ($p->nombre ?? $p->descripcion ?? json_encode($p->getAttributes())) . PHP_EOL;
}
