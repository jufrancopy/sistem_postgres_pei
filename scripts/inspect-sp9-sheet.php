<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$path = __DIR__ . '/../.docs-bio/ESTADISTICA JULIO 2026.xls';
if (! is_file($path)) {
    echo "Missing sample file\n";
    exit(1);
}

$wb = app(App\Application\Bioestadistica\Imports\SpPlanillaParser::class)->scanWorkbook($path);
$sp9 = collect($wb['hojas'])->first(fn ($h) => ($h['sp_codigo'] ?? '') === 'SP9');
if (! $sp9) {
    echo "No SP9 sheet\n";
    exit(1);
}

echo 'parseado: ' . (($sp9['parseado'] ?? false) ? 'yes' : 'no') . PHP_EOL;
if (! empty($sp9['error'])) {
    echo 'error: ' . $sp9['error'] . PHP_EOL;
}
$filas = $sp9['detectado']['filas'] ?? [];
echo 'filas: ' . count($filas) . PHP_EOL;
if ($filas !== []) {
    echo 'sample metrics keys: ' . implode(', ', array_keys($filas[0]['metricas'] ?? $filas[0])) . PHP_EOL;
    echo json_encode(array_slice($filas, 0, 3), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . PHP_EOL;
}
