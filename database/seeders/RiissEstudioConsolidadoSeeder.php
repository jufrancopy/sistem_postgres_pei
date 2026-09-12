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

        // 1. Clasificar y garantizar Secciones Base de las 5 Dimensiones
        $this->crearOActualizarSeccionesBase();

        // 2. Ejecutar Ingestión de Cartera de Servicios desde Excel
        $excelPath = base_path('backups/RIISS/Cartera de servicio estandar consolidado 2026 09 11.xlsx');
        if (!file_exists($excelPath)) {
            $excelPath = base_path('backups/RIISS/Cartera de servicio estandar consolidado 2026 05 09.xlsx');
        }

        if (file_exists($excelPath)) {
            $this->importarDesdeExcel($excelPath);
        } else {
            $this->command->warn("Archivo de Excel no encontrado en {$excelPath}. Se omitió la importación del archivo.");
        }

        $this->command->info('Seeder del Estudio Consolidado completado exitosamente.');
    }

    /**
     * Crea o actualiza secciones estructurales para las 5 dimensiones
     */
    private function crearOActualizarSeccionesBase(): void
    {
        $seccionesBase = [
            // ── GOBERNANZA Y PROCESOS ──
            [
                'seccion'     => 'Datos de Identificación',
                'sub_seccion' => 'Establecimiento y Ubicación',
                'dimension'   => 'gobernanza_procesos',
                'icono'       => 'fa-id-card',
                'orden'       => 1,
            ],
            [
                'seccion'     => 'Requerimientos Documentales',
                'sub_seccion' => 'Habilitación y Normativa Sanitaria',
                'dimension'   => 'gobernanza_procesos',
                'icono'       => 'fa-file-contract',
                'orden'       => 2,
            ],

            // ── INFRAESTRUCTURA E INSTALACIONES ──
            [
                'seccion'     => 'Infraestructura Física',
                'sub_seccion' => 'Edificación, Terreno y Accesibilidad',
                'dimension'   => 'infraestructura',
                'icono'       => 'fa-building',
                'orden'       => 10,
            ],
            [
                'seccion'     => 'Instalaciones y Servicios Básicos',
                'sub_seccion' => 'Redes Hidrosanitarias, Eléctricas y Bioseguridad',
                'dimension'   => 'infraestructura',
                'icono'       => 'fa-bolt',
                'orden'       => 11,
            ],

            // ── TALENTO HUMANO ──
            [
                'seccion'     => 'Talento Humano',
                'sub_seccion' => 'Dotación Médica, Enfermería y Cobertura Horaria',
                'dimension'   => 'talento_humano',
                'icono'       => 'fa-user-doctor',
                'orden'       => 20,
            ],
            [
                'seccion'     => 'Dirección y Regencia',
                'sub_seccion' => 'Equipo de Gestión y Jefaturas de Servicio',
                'dimension'   => 'talento_humano',
                'icono'       => 'fa-users-gear',
                'orden'       => 21,
            ],

            // ── MEDICAMENTOS, INSUMOS Y EQUIPAMIENTO ──
            [
                'seccion'     => 'Medicamentos e Insumos Médicos',
                'sub_seccion' => 'Disponibilidad de Vademécum y Cadena de Frío',
                'dimension'   => 'medicamentos_insumos',
                'icono'       => 'fa-pills',
                'orden'       => 30,
            ],
            [
                'seccion'     => 'Equipamiento Biomédico',
                'sub_seccion' => 'Tecnología, Mantenimiento y Calibración',
                'dimension'   => 'medicamentos_insumos',
                'icono'       => 'fa-microscope',
                'orden'       => 31,
            ],
        ];

        foreach ($seccionesBase as $secData) {
            FormularioSeccion::updateOrCreate(
                [
                    'seccion'     => $secData['seccion'],
                    'sub_seccion' => $secData['sub_seccion']
                ],
                [
                    'dimension' => $secData['dimension'],
                    'icono'     => $secData['icono'],
                    'orden'     => $secData['orden'],
                    'activa'    => true,
                ]
            );
        }
    }

    /**
     * Importa y sincroniza la Cartera de Servicios desde el archivo Excel usando PhpSpreadsheet nativo
     */
    private function importarDesdeExcel(string $filePath): void
    {
        $this->command->info("Leyendo archivo Excel nativamente con PhpSpreadsheet: {$filePath}...");

        $reader = IOFactory::createReaderForFile($filePath);
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($filePath);
        $sheet = $spreadsheet->getSheetByName('COMPILADO detalle') ?: $spreadsheet->getActiveSheet();
        $highestRow = $sheet->getHighestRow();

        $currentPrestacion = '';
        $currentServicio = '';
        $seccionesCache = [];
        $ordenSeccion = 40;
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
                            'seccion'     => 'Cartera de Servicios: ' . mb_substr($grupoNombre, 0, 100),
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
                        'pregunta'              => trim($textoPregunta),
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
