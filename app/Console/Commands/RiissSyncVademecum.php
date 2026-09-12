<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\RiissEspecialidad;
use App\Models\RiissMedicamento;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RiissSyncVademecum extends Command
{
    protected $signature = 'riiss:sync-vademecum {--file=backups/RIISS/RIISS_especialidades/VADEMECUM INSTITUCIONAL DE MEDICAMENTOS DEL IPS 20-08-26.xlsx}';
    protected $description = 'Sincroniza e indexa el Vademécum Oficial Institucional de Medicamentos del IPS (2026)';

    public function handle()
    {
        $filePath = base_path($this->option('file'));
        if (!file_exists($filePath)) {
            $this->error("No se encontró el archivo del Vademécum en: {$filePath}");
            return 1;
        }

        $this->info("================================================================================");
        $this->info("🏥 INICIANDO SINCRONIZACIÓN DEL VADEMÉCUM OFICIAL DE MEDICAMENTOS DEL IPS");
        $this->info("================================================================================");
        $this->line("Archivo fuente: <comment>{$filePath}</comment>");

        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $highestRow = $sheet->getHighestRow();

        $this->info("Leyendo {$highestRow} filas del Vademécum institucional...");

        // 1. Limpiar tabla pivote de Vademécum
        DB::table('riiss_especialidad_vademecum')->truncate();

        // 2. Marcar temporalmente todos los medicamentos en BD como no vademecum
        RiissMedicamento::query()->update(['es_vademecum' => false]);

        // 3. Cachear especialidades existentes en memoria para matching ultrarrápido
        $todasEspecialidades = RiissEspecialidad::all();
        $especialidadesMap = [];
        foreach ($todasEspecialidades as $esp) {
            $nombreNorm = $this->normalizarTexto($esp->nombre);
            $especialidadesMap[$nombreNorm] = $esp->id;
        }

        $countMeds = 0;
        $countVinc = 0;
        $vinculosBatch = [];
        $espsCreadas = 0;

        for ($r = 5; $r <= $highestRow; $r++) {
            $codRaw       = $sheet->getCell("C{$r}")->getValue();
            $descRaw      = $sheet->getCell("D{$r}")->getValue();
            $usoRaw       = $sheet->getCell("B{$r}")->getValue();
            $concRaw      = $sheet->getCell("E{$r}")->getValue();
            $formaRaw     = $sheet->getCell("F{$r}")->getValue();
            $viaRaw       = $sheet->getCell("G{$r}")->getValue();
            $presRaw      = $sheet->getCell("H{$r}")->getValue();
            $unidRaw      = $sheet->getCell("I{$r}")->getValue();
            $espsRaw      = $sheet->getCell("J{$r}")->getValue();
            $resRaw       = $sheet->getCell("K{$r}")->getValue();

            if (empty($codRaw) && empty($descRaw)) {
                continue;
            }

            $codStr = is_numeric($codRaw) ? (string)((int)$codRaw) : trim((string)$codRaw);
            $nombre = trim((string)$descRaw);
            $uso    = trim((string)$usoRaw);
            $conc   = trim((string)$concRaw);
            $forma  = trim((string)$formaRaw);
            $via    = trim((string)$viaRaw);
            $pres   = trim((string)$presRaw);
            $unid   = trim((string)$unidRaw);
            $esps   = trim((string)$espsRaw);
            $res    = trim((string)$resRaw);

            // Actualizar o crear medicamento oficial del Vademécum
            $medicamento = RiissMedicamento::updateOrCreate(
                ['codigo' => $codStr],
                [
                    'nombre'                   => $nombre,
                    'es_vademecum'             => true,
                    'uso_vademecum'            => $uso ?: null,
                    'concentracion'            => $conc ?: null,
                    'forma_farmaceutica'       => $forma ?: null,
                    'via_administracion'       => $via ?: null,
                    'presentacion'             => $pres ?: null,
                    'unidad_medida'            => $unid ?: null,
                    'especialidades_vademecum' => $esps ?: null,
                    'resolucion_respaldo'      => $res ?: null,
                ]
            );

            $countMeds++;

            // Extraer y vincular especialidades autorizadas
            if (!empty($esps)) {
                $tokens = preg_split('/[\-\,\/\n\r]+/', $esps);
                $linkedIds = [];

                foreach ($tokens as $token) {
                    $token = trim($token);
                    if (empty($token) || strlen($token) < 3) continue;

                    $tokenNorm = $this->normalizarTexto($token);

                    // Buscar coincidencia directa en mapa de especialidades
                    $espId = $especialidadesMap[$tokenNorm] ?? null;

                    if (!$espId) {
                        // Búsqueda aproximada / substring
                        foreach ($especialidadesMap as $k => $id) {
                            if ($k === $tokenNorm || str_contains($k, $tokenNorm) || str_contains($tokenNorm, $k)) {
                                $espId = $id;
                                break;
                            }
                        }
                    }

                    // Si la especialidad no existe, crearla para mantener la cobertura completa del vademecum
                    if (!$espId) {
                        $nuevaEsp = RiissEspecialidad::firstOrCreate(
                            ['nombre' => mb_strtoupper($token, 'UTF-8')],
                            ['activo' => true]
                        );
                        $espId = $nuevaEsp->id;
                        $especialidadesMap[$tokenNorm] = $espId;
                        $espsCreadas++;
                    }

                    if ($espId && !in_array($espId, $linkedIds)) {
                        $linkedIds[] = $espId;
                        $vinculosBatch[] = [
                            'especialidad_id' => $espId,
                            'medicamento_id'  => $medicamento->id,
                            'created_at'      => now(),
                            'updated_at'      => now(),
                        ];
                        $countVinc++;
                    }
                }
            }
        }

        // Insertar relaciones pivote en lotes
        foreach (array_chunk($vinculosBatch, 500) as $chunk) {
            DB::table('riiss_especialidad_vademecum')->insertOrIgnore($chunk);
        }

        $this->newLine();
        $this->info("================================================================================");
        $this->info("✅ SINCRONIZACIÓN EXITOSA DEL VADEMÉCUM");
        $this->info("================================================================================");
        $this->table(
            ['Métrica', 'Cantidad'],
            [
                ['Total Medicamentos Vademécum Oficial (2026)', $countMeds],
                ['Total Medicamentos en Base de Datos', RiissMedicamento::count()],
                ['Vínculos Especialidad ↔ Medicamento Normados', $countVinc],
                ['Nuevas Especialidades Catalogadas', $espsCreadas],
                ['Total Especialidades en Sistema', RiissEspecialidad::count()],
            ]
        );

        $this->newLine();
        $this->info("Top 10 Especialidades con más fármacos autorizados en Vademécum:");
        $topEsps = RiissEspecialidad::withCount('medicamentosVademecum')
            ->orderByDesc('medicamentos_vademecum_count')
            ->take(10)
            ->get();

        $tableData = [];
        foreach ($topEsps as $idx => $esp) {
            $tableData[] = [
                $idx + 1,
                $esp->nombre,
                $esp->medicamentos_vademecum_count . ' medicamentos',
            ];
        }
        $this->table(['#', 'Especialidad', 'Medicamentos Vademécum'], $tableData);

        return 0;
    }

    private function normalizarTexto(string $str): string
    {
        $str = mb_strtolower(trim($str), 'UTF-8');
        $str = str_replace(
            ['á', 'é', 'í', 'ó', 'ú', 'ü', 'ñ', 'Á', 'É', 'Í', 'Ó', 'Ú', 'Ü', 'Ñ'],
            ['a', 'e', 'i', 'o', 'u', 'u', 'n', 'a', 'e', 'i', 'o', 'u', 'u', 'n'],
            $str
        );
        $str = preg_replace('/[^a-z0-9]/', '', $str);
        return $str;
    }
}
