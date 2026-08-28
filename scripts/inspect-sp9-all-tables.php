<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$form = App\Models\Bioestadistica\Formulario::where('codigo', 'SP9')
    ->with(['secciones.fields.detalle.prestaciones'])
    ->first();

foreach ($form->secciones->flatMap->fields->where('type', 'tabla') as $field) {
    echo PHP_EOL . $field->code . ' — ' . $field->label . PHP_EOL;
    foreach ($field->detalle->prestaciones as $p) {
        echo '  ' . $p->id . ' | ' . $p->nombre . PHP_EOL;
    }
}
