<?php

namespace Database\Seeders;

use App\Models\Riiss\CarteraServicio;
use App\Models\Riiss\FormularioPregunta;
use App\Models\Riiss\FormularioSeccion;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RiissEstudioConsolidadoSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Iniciando Seeder del Estudio Consolidado RIISS (2026)...');

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
     * Importa y sincroniza la Cartera de Servicios desde el archivo Excel oficial
     */
    private function importarDesdeExcel(string $filePath): void
    {
        $this->command->info("Leyendo archivo Excel: {$filePath}...");

        $tmpJson = storage_path('app/cartera_parsed.json');
        $script = "
import openpyxl, json

wb = openpyxl.load_workbook('{$filePath}', data_only=True)
ws = wb['COMPILADO detalle'] if 'COMPILADO detalle' in wb.sheetnames else wb.active

items = []
current_prestacion = ''
current_servicio = ''

for r in range(33, ws.max_row + 1):
    c1 = ws.cell(r, 1).value
    c2 = ws.cell(r, 2).value
    c3 = ws.cell(r, 3).value
    c4 = ws.cell(r, 4).value
    c6 = str(ws.cell(r, 6).value or '').strip().upper()
    c7 = str(ws.cell(r, 7).value or '').strip().upper()
    c8 = str(ws.cell(r, 8).value or '').strip().upper()
    c9 = str(ws.cell(r, 9).value or '').strip().upper()
    c10 = str(ws.cell(r, 10).value or '').strip().upper()
    c11 = str(ws.cell(r, 11).value or '').strip().upper()
    c12 = ws.cell(r, 12).value

    if c1 and ('NIVEL' in str(c1) or 'Variable Prestación' in str(c1)): continue
    if c2 and ('NIVEL' in str(c2) or 'Servicios' in str(c2)): continue

    if c1 and str(c1).strip(): current_prestacion = str(c1).strip()
    if c2 and str(c2).strip(): current_servicio = str(c2).strip()

    det1 = str(c3).strip() if c3 else ''
    det2 = str(c4).strip() if c4 else ''

    if not det1 and not det2 and not c2: continue

    min_g = 6
    if c6 == 'SI': min_g = min(min_g, 1)
    if c7 == 'SI': min_g = min(min_g, 2)
    if c8 == 'SI': min_g = min(min_g, 3)
    if c9 == 'SI': min_g = min(min_g, 4)
    if c10 == 'SI': min_g = min(min_g, 5)
    if c11 == 'SI': min_g = min(min_g, 6)

    grupo = str(c12).strip() if c12 else current_servicio

    items.append({
        'prestacion': current_prestacion,
        'servicio': current_servicio,
        'detalles': det1,
        'detalles_2': det2,
        'min_grado': min_g,
        'puesto': c6 == 'SI',
        'clinica': c7 == 'SI',
        'unidad': c8 == 'SI',
        'regional': c9 == 'SI',
        'interregional': c10 == 'SI',
        'especializado': c11 == 'SI',
        'grupo': grupo
    })

with open('{$tmpJson}', 'w', encoding='utf-8') as f:
    json.dump(items, f, ensure_ascii=False)
";
        file_put_contents(storage_path('app/parse_excel.py'), $script);
        shell_exec('python3 ' . storage_path('app/parse_excel.py'));

        if (!file_exists($tmpJson)) {
            $this->command->error('Error al parsear el archivo Excel.');
            return;
        }

        $items = json_decode(file_get_contents($tmpJson), true);
        $this->command->info('Se procesarán ' . count($items) . ' registros de Cartera de Servicios...');

        $seccionesCache = [];
        $ordenSeccion = 40;

        DB::beginTransaction();
        try {
            foreach ($items as $idx => $item) {
                $grupoNombre = !empty($item['grupo']) ? trim($item['grupo']) : ($item['servicio'] ?: 'Servicios Asistenciales');
                $subSeccionNombre = $item['servicio'] ?: $item['prestacion'];

                // Crear o reutilizar sección
                $secKey = mb_strtoupper($grupoNombre);
                if (!isset($seccionesCache[$secKey])) {
                    $seccion = FormularioSeccion::firstOrCreate(
                        [
                            'seccion'     => 'Cartera de Servicios: ' . mb_substr($grupoNombre, 0, 40),
                            'sub_seccion' => mb_substr($subSeccionNombre ?: 'General', 0, 100),
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

                $minGrado = (int) $item['min_grado'];
                $nivelAtencion = match($minGrado) {
                    1 => 1,
                    2 => 1,
                    3 => 2,
                    4 => 3,
                    5 => 3,
                    6 => 4,
                    default => 1,
                };

                // Guardar / actualizar en cartera_servicios
                $cartera = CarteraServicio::updateOrCreate(
                    [
                        'variable_prestacion' => mb_substr($item['prestacion'] ?: 'Cartera', 0, 100),
                        'servicio'            => mb_substr($item['servicio'] ?: 'Servicio', 0, 120),
                        'detalles'            => mb_substr($item['detalles'] ?: '', 0, 150),
                        'detalles_2'          => mb_substr($item['detalles_2'] ?: '', 0, 150),
                    ],
                    [
                        'nivel_atencion'           => $nivelAtencion,
                        'grado_complejidad'        => $minGrado,
                        'tipo_establecimiento'     => $minGrado <= 2 ? 'No Hospitalario' : 'Hospitalario',
                        'tipo_prestacion'          => mb_substr($item['prestacion'] ?: 'Cartera de Servicios', 0, 60),
                        'grupo_servicio'           => mb_substr($item['grupo'] ?: 'General', 0, 80),
                        'aplica_puesto_sanitario'  => $item['puesto'],
                        'aplica_clinica_periferica'=> $item['clinica'],
                        'aplica_unidad_sanitaria'  => $item['unidad'],
                        'aplica_hospital_baja'     => $item['regional'],
                        'aplica_hospital_mediana'  => $item['interregional'],
                        'aplica_hospital_alta'     => $item['especializado'],
                        'requerido'                => true,
                    ]
                );

                // Armar texto de la pregunta
                $textoPregunta = $item['servicio'];
                if ($item['detalles']) {
                    $textoPregunta .= ' — ' . $item['detalles'];
                }
                if ($item['detalles_2'] && $item['detalles_2'] !== 'No discriminado') {
                    $textoPregunta .= ' (' . $item['detalles_2'] . ')';
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
                        'orden'                    => $idx + 1,
                        'grado_complejidad_min'    => $minGrado,
                        'es_requerido'             => true,
                        'activa'                   => true,
                        'servicio_cartera_grupo'   => mb_substr($item['grupo'] ?: 'General', 0, 80),
                        'especialidad_relacionada' => mb_substr($item['grupo'] ?: 'General', 0, 80),
                        'tags_cartera'             => [
                            'complejidad_min' => $minGrado,
                            'puesto'          => $item['puesto'],
                            'clinica'         => $item['clinica'],
                            'unidad'          => $item['unidad'],
                            'regional'        => $item['regional'],
                            'interregional'   => $item['interregional'],
                            'especializado'   => $item['especializado'],
                        ],
                        'metadata_cartera'         => [
                            'cartera_id' => $cartera->id,
                            'puesto'     => $item['puesto'],
                            'clinica'    => $item['clinica'],
                            'unidad'     => $item['unidad'],
                            'regional'   => $item['regional'],
                            'interreg'   => $item['interregional'],
                            'especializ' => $item['especializado'],
                        ],
                    ]
                );
            }

            DB::commit();
            $this->command->info('Se importaron exitosamente todos los registros de Cartera de Servicios.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error('Error en transacción de importación: ' . $e->getMessage());
        }

        // Limpiar archivos temporales
        @unlink($tmpJson);
        @unlink(storage_path('app/parse_excel.py'));
    }
}
