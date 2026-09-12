<?php

namespace Database\Seeders;

use App\Models\Riiss\CarteraServicio;
use App\Models\Riiss\FormularioPregunta;
use App\Models\Riiss\FormularioSeccion;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

class RiissEstudioConsolidadoSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Iniciando Seeder del Estudio Consolidado RIISS (2026)...');

        // Garantizar que las columnas tengan longitud suficiente (VARCHAR 255)
        try {
            DB::statement('ALTER TABLE formulario_secciones ALTER COLUMN seccion TYPE VARCHAR(255)');
            DB::statement('ALTER TABLE formulario_secciones ALTER COLUMN sub_seccion TYPE VARCHAR(255)');
            DB::statement('ALTER TABLE cartera_servicios ALTER COLUMN servicio TYPE VARCHAR(255)');
            DB::statement('ALTER TABLE cartera_servicios ALTER COLUMN tipo_prestacion TYPE VARCHAR(255)');
            DB::statement('ALTER TABLE cartera_servicios ALTER COLUMN variable_prestacion TYPE VARCHAR(255)');
            DB::statement('ALTER TABLE cartera_servicios ALTER COLUMN detalles TYPE VARCHAR(255)');
            DB::statement('ALTER TABLE cartera_servicios ALTER COLUMN detalles_2 TYPE VARCHAR(255)');
        } catch (\Exception $e) {
            // Continuar si la BD ya tiene los tipos ajustados
        }

        // 1. Ingestar Secciones y Preguntas de Infraestructura, Talento Humano, Medicamentos y Gobernanza desde Formulario MSPBS
        $mspbsExcel = base_path('backups/RIISS/Formulario para habilitacion Establecimiento sanitario MSPBS.xlsx');
        if (file_exists($mspbsExcel)) {
            $this->importarFormularioMspbs($mspbsExcel);
        }

        // 2. Ingestar Cartera de Servicios desde el Estudio Consolidado
        $carteraExcel = base_path('backups/RIISS/Cartera de servicio estandar consolidado 2026 09 11.xlsx');
        if (!file_exists($carteraExcel)) {
            $carteraExcel = base_path('backups/RIISS/Cartera de servicio estandar consolidado 2026 05 09.xlsx');
        }

        if (file_exists($carteraExcel)) {
            $this->importarCarteraServicios($carteraExcel);
        } else {
            $this->command->warn("Archivo de Cartera no encontrado en {$carteraExcel}.");
        }

        $this->command->info('Seeder del Estudio Consolidado completado exitosamente.');
    }

    /**
     * Ingesta secciones y preguntas de Infraestructura, Talento Humano, Medicamentos y Gobernanza
     */
    private function importarFormularioMspbs(string $filePath): void
    {
        $this->command->info("Importando Banco de Preguntas Estructurales (Infraestructura, Talento, Medicamentos, Gobernanza): {$filePath}...");

        $reader = IOFactory::createReaderForFile($filePath);
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $highestRow = $sheet->getHighestRow();

        $currentSec = '';
        $currentSub = '';
        $seccionesCache = [];
        $ordenSec = 1;
        $totalImportadas = 0;

        DB::beginTransaction();
        try {
            for ($r = 2; $r <= $highestRow; $r++) {
                $c1 = trim((string)$sheet->getCell('A' . $r)->getValue());
                $c2 = trim((string)$sheet->getCell('B' . $r)->getValue());
                $c3 = trim((string)$sheet->getCell('C' . $r)->getValue());

                if ($c1 !== '') $currentSec = $c1;
                if ($c2 !== '') $currentSub = $c2;

                $preguntaTexto = $c3 !== '' ? $c3 : ($c2 !== '' && $c1 !== '' ? $c2 : '');
                if ($preguntaTexto === '' || mb_strtolower($preguntaTexto) === 'preguntas') continue;

                // Clasificar en una de las 5 dimensiones
                $comb = mb_strtolower("{$currentSec} {$currentSub} {$preguntaTexto}");
                if (str_contains($comb, 'edificio') || str_contains($comb, 'terreno') || str_contains($comb, 'baño') || str_contains($comb, 'pared') || str_contains($comb, 'abertura') || str_contains($comb, 'instalaci') || str_contains($comb, 'hidro') || str_contains($comb, 'electr') || str_contains($comb, 'bioseguridad') || str_contains($comb, 'lavander') || str_contains($comb, 'cocina') || str_contains($comb, 'residuo') || str_contains($comb, 'incendio') || str_contains($comb, 'infraestructura') || str_contains($comb, 'sala de espera') || str_contains($comb, 'circulaci') || str_contains($comb, 'ventilaci') || str_contains($comb, 'acceso') || str_contains($comb, 'rampa') || str_contains($comb, 'puerta')) {
                    $dimension = 'infraestructura';
                    $icono = 'fa-building';
                } elseif (str_contains($comb, 'personal') || str_contains($comb, 'médico') || str_contains($comb, 'enfermer') || str_contains($comb, 'rrhh') || str_contains($comb, 'talento') || str_contains($comb, 'regencia') || str_contains($comb, 'director') || str_contains($comb, 'guardia') || str_contains($comb, 'horario') || str_contains($comb, 'administrativ') || str_contains($comb, 'bioquímic') || str_contains($comb, 'odontólog')) {
                    $dimension = 'talento_humano';
                    $icono = 'fa-users';
                } elseif (str_contains($comb, 'medicamento') || str_contains($comb, 'insumo') || str_contains($comb, 'farmacia') || str_contains($comb, 'laboratorio') || str_contains($comb, 'reactivo') || str_contains($comb, 'vacuna') || str_contains($comb, 'cadena de frío') || str_contains($comb, 'termómetro') || str_contains($comb, 'heladera') || str_contains($comb, 'equipamiento') || str_contains($comb, 'ecógrafo') || str_contains($comb, 'rayos') || str_contains($comb, 'respirador')) {
                    $dimension = 'medicamentos_insumos';
                    $icono = 'fa-pills';
                } elseif (str_contains($comb, 'document') || str_contains($comb, 'habilitación') || str_contains($comb, 'resolución') || str_contains($comb, 'patente') || str_contains($comb, 'manual') || str_contains($comb, 'protocolo') || str_contains($comb, 'organigrama') || str_contains($comb, 'registro') || str_contains($comb, 'identificación') || str_contains($comb, 'encargado') || str_contains($comb, 'introducción')) {
                    $dimension = 'gobernanza_procesos';
                    $icono = 'fa-file-shield';
                } else {
                    $dimension = str_contains(mb_strtolower($currentSec), 'datos generales') ? 'infraestructura' : 'cartera_servicios';
                    $icono = $dimension === 'infraestructura' ? 'fa-building' : 'fa-stethoscope';
                }

                $secNombre = $currentSec ?: 'General';
                $subNombre = $currentSub ?: '';
                $secKey = mb_strtoupper("{$secNombre}_{$subNombre}_{$dimension}");

                if (!isset($seccionesCache[$secKey])) {
                    $seccion = FormularioSeccion::firstOrCreate(
                        [
                            'seccion'     => mb_substr($secNombre, 0, 150),
                            'sub_seccion' => mb_substr($subNombre, 0, 200),
                        ],
                        [
                            'dimension' => $dimension,
                            'icono'     => $icono,
                            'orden'     => $ordenSec++,
                            'activa'    => true,
                        ]
                    );
                    if ($seccion->dimension !== $dimension) {
                        $seccion->update(['dimension' => $dimension, 'icono' => $icono]);
                    }
                    $seccionesCache[$secKey] = $seccion;
                } else {
                    $seccion = $seccionesCache[$secKey];
                }

                // Crear o actualizar la pregunta
                FormularioPregunta::firstOrCreate(
                    [
                        'formulario_seccion_id' => $seccion->id,
                        'pregunta'              => mb_substr(trim($preguntaTexto), 0, 1000),
                    ],
                    [
                        'dimension'             => $dimension,
                        'tipo_respuesta'        => 'si_no_na',
                        'orden'                 => $r,
                        'grado_complejidad_min' => 1,
                        'es_requerido'          => true,
                        'activa'                => true,
                        'servicio_cartera_grupo'=> mb_substr($currentSec, 0, 80),
                    ]
                );

                $totalImportadas++;
            }

            DB::commit();
            $this->command->info("Se importaron exitosamente {$totalImportadas} preguntas estructurales clasificadas por dimensión.");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Error al importar Formulario MSPBS: ' . $e->getMessage());
        }
    }

    /**
     * Importa y sincroniza la Cartera de Servicios desde el archivo Excel oficial
     */
    private function importarCarteraServicios(string $filePath): void
    {
        $this->command->info("Leyendo archivo Excel de Cartera de Servicios: {$filePath}...");

        $reader = IOFactory::createReaderForFile($filePath);
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($filePath);
        $sheet = $spreadsheet->getSheetByName('COMPILADO detalle') ?: $spreadsheet->getActiveSheet();
        $highestRow = $sheet->getHighestRow();

        $currentPrestacion = '';
        $currentServicio = '';
        $seccionesCache = [];
        $ordenSeccion = 60;
        $totalProcesados = 0;

        DB::beginTransaction();
        try {
            for ($r = 33; $r <= $highestRow; $r++) {
                $c1 = $sheet->getCell('A' . $r)->getValue();
                $c2 = $sheet->getCell('B' . $r)->getValue();
                $c3 = $sheet->getCell('C' . $r)->getValue();
                $c4 = $sheet->getCell('D' . $r)->getValue();
                $c6 = strtoupper(trim((string)$sheet->getCell('F' . $r)->getValue()));
                $c7 = strtoupper(trim((string)$sheet->getCell('G' . $r)->getValue()));
                $c8 = strtoupper(trim((string)$sheet->getCell('H' . $r)->getValue()));
                $c9 = strtoupper(trim((string)$sheet->getCell('I' . $r)->getValue()));
                $c10 = strtoupper(trim((string)$sheet->getCell('J' . $r)->getValue()));
                $c11 = strtoupper(trim((string)$sheet->getCell('K' . $r)->getValue()));
                $c12 = $sheet->getCell('L' . $r)->getValue();

                $strC1 = trim((string)$c1);
                $strC2 = trim((string)$c2);

                if (str_contains($strC1, 'NIVEL') || str_contains($strC1, 'Variable Prestación')) continue;
                if (str_contains($strC2, 'NIVEL') || str_contains($strC2, 'Servicios')) continue;

                if ($strC1 !== '') $currentPrestacion = $strC1;
                if ($strC2 !== '') $currentServicio = $strC2;

                $det1 = trim((string)$c3);
                $det2 = trim((string)$c4);

                if ($det1 === '' && $det2 === '' && $strC2 === '') continue;

                $minGrado = 6;
                if ($c6 === 'SI') $minGrado = min($minGrado, 1);
                if ($c7 === 'SI') $minGrado = min($minGrado, 2);
                if ($c8 === 'SI') $minGrado = min($minGrado, 3);
                if ($c9 === 'SI') $minGrado = min($minGrado, 4);
                if ($c10 === 'SI') $minGrado = min($minGrado, 5);
                if ($c11 === 'SI') $minGrado = min($minGrado, 6);

                $grupo = trim((string)$c12) ?: ($currentServicio ?: 'General');
                $grupoNombre = $grupo ?: 'Servicios Asistenciales';
                $subSeccionNombre = $currentServicio ?: $currentPrestacion;

                // Crear o reutilizar sección
                $secKey = mb_strtoupper($grupoNombre);
                if (!isset($seccionesCache[$secKey])) {
                    $seccion = FormularioSeccion::firstOrCreate(
                        [
                            'seccion'     => 'Cartera: ' . mb_substr($grupoNombre, 0, 100),
                            'sub_seccion' => mb_substr($subSeccionNombre ?: 'General', 0, 200),
                        ],
                        [
                            'dimension' => 'cartera_servicios',
                            'icono'     => 'fa-stethoscope',
                            'orden'     => $ordenSeccion++,
                            'activa'    => true,
                        ]
                    );
                    $seccionesCache[$secKey] = $seccion;
                } else {
                    $seccion = $seccionesCache[$secKey];
                }

                $nivelAtencion = match($minGrado) {
                    1 => 1,
                    2 => 1,
                    3 => 2,
                    4 => 3,
                    5 => 3,
                    6 => 4,
                    default => 1,
                };

                $puesto = ($c6 === 'SI');
                $clinica = ($c7 === 'SI');
                $unidad = ($c8 === 'SI');
                $regional = ($c9 === 'SI');
                $interregional = ($c10 === 'SI');
                $especializado = ($c11 === 'SI');

                // Guardar / actualizar en cartera_servicios
                $cartera = CarteraServicio::updateOrCreate(
                    [
                        'variable_prestacion' => mb_substr($currentPrestacion ?: 'Cartera', 0, 255),
                        'servicio'            => mb_substr($currentServicio ?: 'Servicio', 0, 255),
                        'detalles'            => mb_substr($det1, 0, 255),
                        'detalles_2'          => mb_substr($det2, 0, 255),
                    ],
                    [
                        'nivel_atencion'           => $nivelAtencion,
                        'grado_complejidad'        => $minGrado,
                        'tipo_establecimiento'     => $minGrado <= 2 ? 'No Hospitalario' : 'Hospitalario',
                        'tipo_prestacion'          => mb_substr($currentPrestacion ?: 'Cartera de Servicios', 0, 150),
                        'grupo_servicio'           => mb_substr($grupo, 0, 80),
                        'aplica_puesto_sanitario'  => $puesto,
                        'aplica_clinica_periferica'=> $clinica,
                        'aplica_unidad_sanitaria'  => $unidad,
                        'aplica_hospital_baja'     => $regional,
                        'aplica_hospital_mediana'  => $interregional,
                        'aplica_hospital_alta'     => $especializado,
                        'requerido'                => true,
                    ]
                );

                // Armar texto de la pregunta
                $textoPregunta = $currentServicio;
                if ($det1 !== '') {
                    $textoPregunta .= ' — ' . $det1;
                }
                if ($det2 !== '' && $det2 !== 'No discriminado') {
                    $textoPregunta .= ' (' . $det2 . ')';
                }

                // Crear o vincular pregunta en formulario_preguntas
                FormularioPregunta::firstOrCreate(
                    [
                        'formulario_seccion_id' => $seccion->id,
                        'pregunta'              => mb_substr(trim($textoPregunta), 0, 1000),
                    ],
                    [
                        'dimension'                => 'cartera_servicios',
                        'tipo_respuesta'           => 'si_no_na',
                        'orden'                    => $totalProcesados + 1,
                        'grado_complejidad_min'    => $minGrado,
                        'es_requerido'             => true,
                        'activa'                   => true,
                        'servicio_cartera_grupo'   => mb_substr($grupo, 0, 80),
                        'especialidad_relacionada' => mb_substr($grupo, 0, 80),
                        'tags_cartera'             => [
                            'complejidad_min' => $minGrado,
                            'puesto'          => $puesto,
                            'clinica'         => $clinica,
                            'unidad'          => $unidad,
                            'regional'        => $regional,
                            'interregional'   => $interregional,
                            'especializado'   => $especializado,
                        ],
                        'metadata_cartera'         => [
                            'cartera_id' => $cartera->id,
                            'puesto'     => $puesto,
                            'clinica'    => $clinica,
                            'unidad'     => $unidad,
                            'regional'   => $regional,
                            'interreg'   => $interregional,
                            'especializ' => $especializado,
                        ],
                    ]
                );

                $totalProcesados++;
            }

            DB::commit();
            $this->command->info("Se procesaron y guardaron exitosamente {$totalProcesados} registros de Cartera de Servicios.");
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Error en transacción de importación: ' . $e->getMessage());
        }
    }
}
