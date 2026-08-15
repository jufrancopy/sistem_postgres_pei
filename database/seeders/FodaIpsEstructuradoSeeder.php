<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Admin\Planificacion\Foda\FodaPerfil;
use App\Admin\Planificacion\Foda\FodaModelo;
use App\Admin\Planificacion\Foda\FodaAnalisis;

class FodaIpsEstructuradoSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('Iniciando Seeder del Modelo FODA Ideal para IPS Paraguay...');

        // 1. Obtener o verificar el perfil consolidado IPS
        $perfilId = '9a741ae1-95b2-4519-bb2c-6d4fdcc4b59e';
        $perfil = FodaPerfil::find($perfilId);

        if (!$perfil) {
            $perfil = FodaPerfil::create([
                'id'          => $perfilId,
                'name'        => 'ANÁLISIS FODA IPS',
                'type'        => 'consolidado',
                'description' => 'Modelo FODA Estructurado con IEA y Fichas MECIP 2015 para IPS Paraguay',
            ]);
        }

        // 2. Obtener nodo Raíz 'Analisis FODA'
        $root = FodaModelo::where('name', 'Analisis FODA')->first();
        if (!$root) {
            $root = FodaModelo::create([
                'name'  => 'Analisis FODA',
                'type'  => 'root',
                'owner' => 'IPS',
            ]);
        }

        // Definición completa de Categorías y Aspectos con Evidencia IEA y MECIP 2015
        $estructura = [
            // ── AMBIENTE INTERNO ──
            [
                'categoria' => 'Abastecimiento y Farmacia (Medicamentos e Insumos)',
                'environment' => 'Interno',
                'aspectos' => [
                    [
                        'name'        => 'Stock y Trazabilidad de Medicamentos Oncológicos y Crónicos',
                        'environment' => 'Interno',
                        'description' => '<p><strong>Evidencia/Referencia IEA:</strong> Desabastecimiento recurrente en el Cuadro Básico IPS afectando a más de 12.000 pacientes oncológicos y crónicos. Licitaciones con retrasos administrativos de hasta 8 meses y cuellos de botella logísticos.</p>',
                        'tipo'        => 'Debilidad',
                        'desempeno'   => 0.35,
                        'inversion'   => 1.00,
                        'ocurrencia'  => 0.90,
                        'impacto'     => 0.80,
                        'causa_raiz'  => 'normativa',
                        'accion'      => 'Establecer contratos abiertos multianuales con cláusulas de suministro de emergencia e informatización del inventario en tiempo real en la red nacional IPS.',
                        'control'     => 'Auditoría semanal de quiebre de stock con alerta temprana conectada a la Dirección de Compras e Insumos Médicos.',
                    ],
                    [
                        'name'        => 'Cadena de Frío e Infraestructura Logística en Parques Sanitarios',
                        'environment' => 'Interno',
                        'description' => '<p><strong>Evidencia/Referencia IEA:</strong> Modernización de la cámara frigorífica principal del Parque Sanitario Central y certificación ISO de trazabilidad térmica para vacunas e insumos biológicos.</p>',
                        'tipo'        => 'Fortaleza',
                        'desempeno'   => 0.85,
                        'inversion'   => 0.60,
                        'ocurrencia'  => 0.70,
                        'impacto'     => 0.40,
                        'causa_raiz'  => null,
                        'accion'      => null,
                        'control'     => null,
                    ],
                ]
            ],
            [
                'categoria' => 'Atención Asistencial, RR.HH. y Agendamiento',
                'environment' => 'Interno',
                'aspectos' => [
                    [
                        'name'        => 'Agendamiento de Turnos Asistenciales y Servicio Call Center',
                        'environment' => 'Interno',
                        'description' => '<p><strong>Evidencia/Referencia IEA:</strong> Saturación de líneas de atención telefónica (esperas superiores a 45 min) e ineficiencia en el agendamiento web, generando demoras de hasta 90 días para consultas con especialistas.</p>',
                        'tipo'        => 'Debilidad',
                        'desempeno'   => 0.25,
                        'inversion'   => 1.00,
                        'ocurrencia'  => 0.90,
                        'impacto'     => 0.80,
                        'causa_raiz'  => 'tecnologica',
                        'accion'      => 'Migración integral a plataforma omnichannel cloud con agendamiento vía WhatsApp institucional, App IPS y kioscos biométricos descentralizados.',
                        'control'     => 'Monitoreo en tiempo real del nivel de servicio (SLA) del Call Center con panel de control gerencial.',
                    ],
                    [
                        'name'        => 'Productividad Médica y Cobertura de Personal Blanco',
                        'environment' => 'Interno',
                        'description' => '<p><strong>Evidencia/Referencia IEA:</strong> Déficit de perfiles médicos especialistas en hospitales regionales y ausentismo no programado en guardias críticas de Urgencias y Terapias.</p>',
                        'tipo'        => 'Debilidad',
                        'desempeno'   => 0.40,
                        'inversion'   => 1.00,
                        'ocurrencia'  => 0.70,
                        'impacto'     => 0.80,
                        'causa_raiz'  => 'estructural',
                        'accion'      => 'Rediseño del manual de funciones y puestos con incentivos por radicación en el interior y control biométrico de cumplimiento de guardias.',
                        'control'     => 'Sistema automatizado de marcación de asistencia integrado a la liquidación de haberes y auditorías médicas aleatorias.',
                    ],
                ]
            ],
            [
                'categoria' => 'Fondo de Jubilaciones y Pensiones',
                'environment' => 'Interno',
                'aspectos' => [
                    [
                        'name'        => 'Cobranza y Combate a la Evasión y Mora Patronal',
                        'environment' => 'Interno',
                        'description' => '<p><strong>Evidencia/Referencia IEA:</strong> Mora patronal acumulada estimada en más de USD 400 millones por retención o no pago de aportes obrero-patronales de empresas privadas e instituciones.</p>',
                        'tipo'        => 'Debilidad',
                        'desempeno'   => 0.30,
                        'inversion'   => 1.00,
                        'ocurrencia'  => 0.90,
                        'impacto'     => 0.80,
                        'causa_raiz'  => 'operativa',
                        'accion'      => 'Intensificación de fiscalizaciones laborales cruzadas con el Ministerio de Trabajo y Abogacía del Tesoro, e informatización de certificados de no adeudar.',
                        'control'     => 'Emisión automática de certificados de deuda con fuerza ejecutiva penal y bloqueo de contratos estatales a empresas morosas.',
                    ],
                    [
                        'name'        => 'Rentabilidad e Inversión Técnica del Fondo de Reserva Jubilatorio',
                        'environment' => 'Interno',
                        'description' => '<p><strong>Evidencia/Referencia IEA:</strong> Mantenimiento del portafolio inmobiliario y financiero de reservas técnicas con colocación de bonos en el sistema bancario nacional.</p>',
                        'tipo'        => 'Fortaleza',
                        'desempeno'   => 0.80,
                        'inversion'   => 0.65,
                        'ocurrencia'  => 0.70,
                        'impacto'     => 0.40,
                        'causa_raiz'  => null,
                        'accion'      => null,
                        'control'     => null,
                    ],
                ]
            ],
            [
                'categoria' => 'Equipamiento e Infraestructura Hospitalaria',
                'environment' => 'Interno',
                'aspectos' => [
                    [
                        'name'        => 'Mantenimiento Operativo de Equipos Biomédicos de Alta Complejidad',
                        'environment' => 'Interno',
                        'description' => '<p><strong>Evidencia/Referencia IEA:</strong> Parada prolongada de tomógrafos, angiógrafos y aceleradores lineales por retrasos en contratos de mantenimiento preventivo y repuestos importados.</p>',
                        'tipo'        => 'Debilidad',
                        'desempeno'   => 0.35,
                        'inversion'   => 1.00,
                        'ocurrencia'  => 0.70,
                        'impacto'     => 0.80,
                        'causa_raiz'  => 'normativa',
                        'accion'      => 'Licitaciones plurianuales de mantenimiento integral con fabricante original garantizando disponibilidad mínima del 95% operativa.',
                        'control'     => 'Mesa técnica de monitoreo diario del estado operativo de equipos de imagenología y soporte vital en toda la red.',
                    ],
                ]
            ],

            // ── AMBIENTE EXTERNO ──
            [
                'categoria' => 'Entorno Normativo, Macroeconómico y Demográfico',
                'environment' => 'Externo',
                'aspectos' => [
                    [
                        'name'        => 'Alta Informalidad Laboral en el Mercado del Trabajo Nacional',
                        'environment' => 'Externo',
                        'description' => '<p><strong>Evidencia/Referencia IEA:</strong> Tasa de informalidad laboral superior al 60% en Paraguay, limitando el crecimiento de la base de cotizantes e ingresos del seguro social.</p>',
                        'tipo'        => 'Amenaza',
                        'desempeno'   => 0.30,
                        'inversion'   => 1.00,
                        'ocurrencia'  => 0.90,
                        'impacto'     => 0.80,
                        'causa_raiz'  => 'normativa',
                        'accion'      => 'Impulsar reformas legislativas de fiscalización conjunta MTESS/IPS e incentivos a la formalización de MiPyMES.',
                        'control'     => 'Intercambio automático de bases de datos tributarias con la Dirección Nacional de Ingresos Tributarios (DNIT).',
                    ],
                    [
                        'name'        => 'Transición Epidemiológica y Envejecimiento Poblacional',
                        'environment' => 'Externo',
                        'description' => '<p><strong>Evidencia/Referencia IEA:</strong> Aumento sostenido en la expectativa de vida y prevalencia de patologías crónicas no transmisibles (cardiovasculares, diabetes, cáncer), incrementando los costos médicos por jubilado.</p>',
                        'tipo'        => 'Amenaza',
                        'desempeno'   => 0.40,
                        'inversion'   => 1.00,
                        'ocurrencia'  => 0.90,
                        'impacto'     => 0.80,
                        'causa_raiz'  => 'estructural',
                        'accion'      => 'Reforzamiento de la atención primaria de salud (APS) y programas preventivos institucionales para reducir complicaciones crónicas.',
                        'control'     => 'Ficha clínica digital preventiva por asegurado con alertas de seguimiento metabólico.',
                    ],
                    [
                        'name'        => 'Disponibilidad de Herramientas Tecnológicas y Datos Abiertos',
                        'environment' => 'Externo',
                        'description' => '<p><strong>Evidencia/Referencia IEA:</strong> Evolución de ecosistemas de firma digital, datos abiertos e inteligencia artificial aplicable al diagnóstico médico y auditoría contable.</p>',
                        'tipo'        => 'Oportunidad',
                        'desempeno'   => 0.90,
                        'inversion'   => 0.60,
                        'ocurrencia'  => 0.70,
                        'impacto'     => 0.40,
                        'causa_raiz'  => null,
                        'accion'      => null,
                        'control'     => null,
                    ],
                ]
            ],
        ];

        foreach ($estructura as $catData) {
            // Buscar o crear categoría
            $cat = FodaModelo::where('name', $catData['categoria'])
                ->where('parent_id', $root->id)
                ->first();

            if (!$cat) {
                $cat = FodaModelo::create([
                    'name'        => $catData['categoria'],
                    'type'        => 'category',
                    'owner'       => 'IPS',
                    'environment' => $catData['environment'],
                    'parent_id'   => $root->id,
                ]);
            } else {
                $cat->update(['environment' => $catData['environment'], 'owner' => 'IPS']);
            }

            foreach ($catData['aspectos'] as $aspData) {
                // Buscar o crear aspecto en FodaModelo
                $aspecto = FodaModelo::where('name', $aspData['name'])
                    ->where('parent_id', $cat->id)
                    ->first();

                if (!$aspecto) {
                    $aspecto = FodaModelo::create([
                        'name'        => $aspData['name'],
                        'type'        => 'aspect',
                        'owner'       => 'IPS',
                        'environment' => $aspData['environment'],
                        'description' => $aspData['description'],
                        'parent_id'   => $cat->id,
                    ]);
                } else {
                    $aspecto->update([
                        'owner'       => 'IPS',
                        'environment' => $aspData['environment'],
                        'description' => $aspData['description'],
                    ]);
                }

                // Calcular IEA
                $desempeno = $aspData['desempeno'];
                $inversion = $aspData['inversion'];
                $ieaValor  = round($desempeno / $inversion, 4);

                $ieaClasif = 'neutro';
                if ($ieaValor < 0.4) {
                    $ieaClasif = 'debilidad';
                } elseif ($ieaValor > 0.8) {
                    $ieaClasif = 'fortaleza';
                }

                // Crear o actualizar FodaAnalisis
                $analisis = FodaAnalisis::where('perfil_id', $perfil->id)
                    ->where('aspecto_id', $aspecto->id)
                    ->first();

                $datosAnalisis = [
                    'user_id'                 => 1,
                    'perfil_id'               => $perfil->id,
                    'aspecto_id'              => $aspecto->id,
                    'tipo'                    => $aspData['tipo'],
                    'ocurrencia'              => $aspData['ocurrencia'],
                    'impacto'                 => $aspData['impacto'],
                    'promedio_desempeno_6m'   => $desempeno,
                    'inversion_historica_6m' => $inversion,
                    'iea_valor'               => $ieaValor,
                    'iea_clasificacion'       => $ieaClasif,
                    'causa_raiz'              => $aspData['causa_raiz'],
                    'accion_mejora'           => $aspData['accion'],
                    'control_preventivo'      => $aspData['control'],
                ];

                if (!$analisis) {
                    FodaAnalisis::create($datosAnalisis);
                } else {
                    $analisis->update($datosAnalisis);
                }
            }
        }

        $this->command->info('¡Seeder del Modelo FODA e IEA para IPS Paraguay completado exitosamente!');
    }
}
