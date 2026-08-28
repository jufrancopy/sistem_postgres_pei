<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$form = App\Models\Bioestadistica\Formulario::where('codigo', 'SP9')
    ->with(['secciones.fields.detalle.prestaciones'])
    ->first();

if (! $form) {
    echo "No SP9 form\n";
    exit(1);
}

foreach ($form->secciones as $sec) {
    echo 'Seccion: ' . $sec->titulo . PHP_EOL;
    foreach ($sec->fields as $field) {
        if ($field->type !== 'tabla') {
            continue;
        }
        echo '  Field: ' . $field->code . ' — ' . $field->label . PHP_EOL;
        echo '  Columns: ' . json_encode($field->config['columns'] ?? [], JSON_UNESCAPED_UNICODE) . PHP_EOL;
        $prestaciones = $field->detalle?->prestaciones ?? collect();
        echo '  Prestaciones (' . $prestaciones->count() . '):' . PHP_EOL;
        foreach ($prestaciones->take(15) as $p) {
            echo '    - ' . $p->label . PHP_EOL;
        }
        if ($prestaciones->count() > 15) {
            echo '    ... +' . ($prestaciones->count() - 15) . PHP_EOL;
        }
    }
}
