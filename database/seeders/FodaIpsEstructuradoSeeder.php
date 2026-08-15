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
        $this->command->info('Iniciando Seeder del Modelo FODA Exhaustivo e Integral para IPS Paraguay...');

        // Perfiles objetivo para poblar
        $targetPerfilIds = [
            '9a741ae1-95b2-4519-bb2c-6d4fdcc4b59e', // ANÁLISIS FODA IPS (Consolidado)
            'a0e99967-c8d5-4d27-adac-cf16fc9efbb3', // [DEMO] Análisis FODA — IPS 2025 (Grupal)
            'a24b1239-b102-410d-be8c-eabc06a5c079', // Nasim Barry (Individual)
        ];

        foreach ($targetPerfilIds as $pId) {
            $perfil = FodaPerfil::find($pId);
            if (!$perfil) {
                FodaPerfil::create([
                    'id'          => $pId,
                    'name'        => 'ANÁLISIS FODA IPS',
                    'type'        => 'consolidado',
                    'description' => 'Modelo FODA Exhaustivo con IEA y Fichas MECIP 2015 para IPS Paraguay',
                ]);
            }
        }

        // Obtener o actualizar nodo Raíz 'MODELO FODA ESTRUCTURADO — IPS PARAGUAY'
        $root = FodaModelo::find(1);
        if (!$root) {
            $root = FodaModelo::where('type', 'root')->first();
        }

        if ($root) {
            $root->update([
                'name'        => 'MODELO FODA ESTRUCTURADO — IPS PARAGUAY',
                'type'        => 'root',
                'owner'       => 'Instituto de Previsión Social',
                'description' => '<p>Modelo FODA Exhaustivo de Alta Complejidad basado en denuncias periodísticas, informes de auditoría Contraloría/MSPBS, Índice de Eficiencia de Activos (IEA) y Gestión de Riesgos MECIP 2015.</p>',
            ]);
        } else {
            $root = FodaModelo::create([
                'name'        => 'MODELO FODA ESTRUCTURADO — IPS PARAGUAY',
                'type'        => 'root',
                'owner'       => 'Instituto de Previsión Social',
                'description' => '<p>Modelo FODA Exhaustivo de Alta Complejidad basado en denuncias periodísticas, informes de auditoría Contraloría/MSPBS, Índice de Eficiencia de Activos (IEA) y Gestión de Riesgos MECIP 2015.</p>',
            ]);
        }

        // Matriz Exhaustiva de Categorías y Aspectos
        $estructura = [

            // ── CATEGORÍA 1: Talento Humano y Carga Horaria Médica ──
            [
                'categoria' => 'Talento Humano y Carga Horaria Médica',
                'environment' => 'Interno',
                'aspectos' => [
                    [
                        'name'        => 'Reducción de Carga Horaria Médica (Ley 12hs) y Cobertura de Guardias',
                        'environment' => 'Interno',
                        'description' => '<p><strong>Evidencia/Auditoría:</strong> Exigencia y aplicación progresiva de la carga horaria de 12 horas semanales por vínculo para médicos sin contar con el presupuesto ni el plantel adicional necesario. Genera baches críticos en guardias de Urgencias, UTI y anestesiología en el Hospital Central y clínicas periféricas, provocando suspensión de cirugías y diferimiento de turnos.</p>',
                        'tipo'        => 'Debilidad',
                        'desempeno'   => 0.30,
                        'inversion'   => 1.00,
                        'ocurrencia'  => 0.90,
                        'impacto'     => 0.80,
                        'causa_raiz'  => 'estructural',
                        'accion'      => 'Crear la carrera administrativa médica del IPS con concursos de méritos para contratación de médicos de refuerzo y reorganización de guardias por red asistencial.',
                        'control'     => 'Panel de control biométrico de cobertura en tiempo real de guardias críticas en Urgencias y UTI.',
                    ],
                    [
                        'name'        => 'Fuga de Talento Humano de Blanco por Brecha Salarial e Incentivos',
                        'environment' => 'Interno',
                        'description' => '<p><strong>Evidencia/Periodística:</strong> Emigración constante de especialistas y subespecialistas (neonatólogos, cirujanos pediátricos, oncólogos) hacia el sector privado y el MSPBS por diferencias salariales, falta de incentivos por desarraigo y sobrecarga laboral asistencial.</p>',
                        'tipo'        => 'Debilidad',
                        'desempeno'   => 0.35,
                        'inversion'   => 1.00,
                        'ocurrencia'  => 0.90,
                        'impacto'     => 0.80,
                        'causa_raiz'  => 'estructural',
                        'accion'      => 'Implementar un esquema de incentivos salariales por especialidad crítica y radicación en hospitales del interior (Alto Paraná, Itapúa, Boquerón).',
                        'control'     => 'Evaluación semestral de tasa de retención de personal de blanco y clima laboral en especialidades sensibles.',
                    ],
                    [
                        'name'        => 'Centro de Formación de Médicos Residentes y Subespecialidades (Hospital Central)',
                        'environment' => 'Interno',
                        'description' => '<p><strong>Evidencia/Referencia IEA:</strong> El Hospital Central del IPS se mantiene como el mayor centro de formación e internado de médicos residentes del país en más de 35 especialidades médicas con acreditación ANEAES.</p>',
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

            // ── CATEGORÍA 2: Abastecimiento, Licitaciones y Cadena Farmacéutica ──
            [
                'categoria' => 'Abastecimiento, Licitaciones y Cadena Farmacéutica',
                'environment' => 'Interno',
                'aspectos' => [
                    [
                        'name'        => 'Pasivos con Proveedores y Quiebre de Stock de Fármacos Oncológicos y Crónicos',
                        'environment' => 'Interno',
                        'description' => '<p><strong>Evidencia/Auditoría:</strong> Deuda acumulada con cámaras farmacéuticas (Cifarma/Cimefor) superior a USD 250 millones, generando cortes parciales en el suministro de insumos y fármacos oncológicos. Trámites licitatorios en la DNCP con demoras de 6 a 9 meses.</p>',
                        'tipo'        => 'Debilidad',
                        'desempeno'   => 0.25,
                        'inversion'   => 1.00,
                        'ocurrencia'  => 0.90,
                        'impacto'     => 0.80,
                        'causa_raiz'  => 'normativa',
                        'accion'      => 'Establecer licitaciones con contratos abiertos pluri-anuales, fideicomiso de pagos garantizados e inventario automatizado conectando parques sanitarios con farmacias asistenciales.',
                        'control'     => 'Auditoría semanal de stock de medicamentos esenciales del Cuadro Básico IPS con alertas automáticas ante niveles mínimos.',
                    ],
                    [
                        'name'        => 'Alto Costo por Tercerización de Diálisis y Laboratorios Externos',
                        'environment' => 'Interno',
                        'description' => '<p><strong>Evidencia/Periodística:</strong> Insuficiencia de sillones de hemodiálisis y reactivos de laboratorio propios, obligando a contratos millonarios de tercerización con sanatorios privados con sobrenivel de costo por paciente.</p>',
                        'tipo'        => 'Debilidad',
                        'desempeno'   => 0.38,
                        'inversion'   => 1.00,
                        'ocurrencia'  => 0.70,
                        'impacto'     => 0.80,
                        'causa_raiz'  => 'operativa',
                        'accion'      => 'Plan de expansión de salas de nefrología y automatización de laboratorios centrales en los hospitales periféricos de Ingavi y Luque.',
                        'control'     => 'Auditoría médica de validación de prestaciones tercerizadas e indicadores de costo por sesión.',
                    ],
                    [
                        'name'        => 'Parque Sanitario Central y Sistema de Cadena de Frío Certificado',
                        'environment' => 'Interno',
                        'description' => '<p><strong>Evidencia/Referencia IEA:</strong> Infraestructura frigorífica principal certificada con control digital continuo de temperatura para conservación de biológicos e insumos sensibles.</p>',
                        'tipo'        => 'Fortaleza',
                        'desempeno'   => 0.88,
                        'inversion'   => 0.60,
                        'ocurrencia'  => 0.70,
                        'impacto'     => 0.40,
                        'causa_raiz'  => null,
                        'accion'      => null,
                        'control'     => null,
                    ],
                ]
            ],

            // ── CATEGORÍA 3: Agendamiento, Tecnología e Historia Clínica Digital ──
            [
                'categoria' => 'Agendamiento, Tecnología e Historia Clínica Digital',
                'environment' => 'Interno',
                'aspectos' => [
                    [
                        'name'        => 'Saturación del Call Center y Fallas del Sistema SIH en Agendamiento',
                        'environment' => 'Interno',
                        'description' => '<p><strong>Evidencia/Denuncias:</strong> Tiempos de espera telefónica superiores a 45 minutos y caídas recurrentes del Sistema de Información Hospitalaria (SIH). Provoca demoras de 3 a 6 meses para agendar especialidades en traumatología, endocrinología y neurología.</p>',
                        'tipo'        => 'Debilidad',
                        'desempeno'   => 0.20,
                        'inversion'   => 1.00,
                        'ocurrencia'  => 0.90,
                        'impacto'     => 0.80,
                        'causa_raiz'  => 'tecnologica',
                        'accion'      => 'Migrar a una plataforma omnicanal en la nube integrando chatbot por WhatsApp, App Mi IPS renovada y kioscos de auto-agendamiento en hospitales.',
                        'control'     => 'Tablero gerencial de monitoreo de respuesta telefónica e incidencias del sistema informático SIH.',
                    ],
                    [
                        'name'        => 'Despliegue del Expediente Clínico Electrónico y Receta Digital',
                        'environment' => 'Externo',
                        'description' => '<p><strong>Evidencia/Referencia IEA:</strong> Integración gradual de la receta médica electrónica y la ficha clínica digital conectada con el Metic y el MSPBS para reducir fraudes y tiempos en ventanilla.</p>',
                        'tipo'        => 'Oportunidad',
                        'desempeno'   => 0.90,
                        'inversion'   => 0.55,
                        'ocurrencia'  => 0.70,
                        'impacto'     => 0.40,
                        'causa_raiz'  => null,
                        'accion'      => null,
                        'control'     => null,
                    ],
                ]
            ],

            // ── CATEGORÍA 4: Fondo de Jubilaciones, Pensiones y Sostenibilidad Financiera ──
            [
                'categoria' => 'Fondo de Jubilaciones, Pensiones y Sostenibilidad Financiera',
                'environment' => 'Interno',
                'aspectos' => [
                    [
                        'name'        => 'Déficit Actuarial y Riesgo en la Reserva del Fondo de Jubilaciones',
                        'environment' => 'Externo',
                        'description' => '<p><strong>Evidencia/Informes Actuariales:</strong> Estudios actuariales advierten el agotamiento gradual de las reservas técnicas para el 2030-2038 debido al aumento de la expectativa de vida, envejecimiento de asegurados y rigidez en los parámetros de cálculo jubilatorio.</p>',
                        'tipo'        => 'Amenaza',
                        'desempeno'   => 0.30,
                        'inversion'   => 1.00,
                        'ocurrencia'  => 0.90,
                        'impacto'     => 0.80,
                        'causa_raiz'  => 'normativa',
                        'accion'      => 'Promover una reforma integral de la Ley Orgánica del IPS ajustando la fórmula de cálculo del haber jubilatorio sobre el promedio de los últimos 10 años y diversificando las inversiones.',
                        'control'     => 'Comité de supervisión actuarial independiente con publicación trimestral del estado de reservas.',
                    ],
                    [
                        'name'        => 'Mora Patronal Acumulada, Evasión y Subdeclaración de Salarios',
                        'environment' => 'Interno',
                        'description' => '<p><strong>Evidencia/Auditoría Contraloría:</strong> Mora patronal acumulada estimada en más de USD 400 millones de empresas e instituciones públicas. Práctica extendida de subdeclaración de salarios de trabajadores para evadir aportes reales.</p>',
                        'tipo'        => 'Debilidad',
                        'desempeno'   => 0.28,
                        'inversion'   => 1.00,
                        'ocurrencia'  => 0.90,
                        'impacto'     => 0.80,
                        'causa_raiz'  => 'operativa',
                        'accion'      => 'Cruzamiento informático automático de datos con la DNIT (Tributación) y el Ministerio de Trabajo (MTESS) con bloqueos judiciales inmediatos.',
                        'control'     => 'Sistema de emisión de Certificados de Deuda automatizados con fuerza ejecutiva penal para cobro cobratorio.',
                    ],
                    [
                        'name'        => 'Deuda Histórica del Estado Paraguayo con el Fondo de Salud del IPS',
                        'environment' => 'Externo',
                        'description' => '<p><strong>Evidencia/Prensa:</strong> Acumulación de deuda histórica del Estado paraguayo por la atención médica brindada a no asegurados y emergencias nacionales sin la transferencia correspondiente del Tesoro Público.</p>',
                        'tipo'        => 'Amenaza',
                        'desempeno'   => 0.32,
                        'inversion'   => 1.00,
                        'ocurrencia'  => 0.90,
                        'impacto'     => 0.80,
                        'causa_raiz'  => 'normativa',
                        'accion'      => 'Mesas multipartitarias de negociación con el Ministerio de Economía (MEF) para la consolidación y canje de la deuda con títulos del Tesoro o inmuebles.',
                        'control'     => 'Comisión interinstitucional de conciliación de cuentas de salud pública vs. seguro social.',
                    ],
                    [
                        'name'        => 'Portafolio Financiero y Rendimiento de Reservas Inmobiliarias',
                        'environment' => 'Interno',
                        'description' => '<p><strong>Evidencia/Referencia IEA:</strong> Inversión en CDA bancarios del sistema financiero nacional generando rendimientos que mitigan la inflación sobre las reservas líquidas.</p>',
                        'tipo'        => 'Fortaleza',
                        'desempeno'   => 0.82,
                        'inversion'   => 0.65,
                        'ocurrencia'  => 0.70,
                        'impacto'     => 0.40,
                        'causa_raiz'  => null,
                        'accion'      => null,
                        'control'     => null,
                    ],
                ]
            ],

            // ── CATEGORÍA 5: Infraestructura Hospitalaria, Equipos y Servicios Generales ──
            [
                'categoria' => 'Infraestructura Hospitalaria, Equipos y Servicios Generales',
                'environment' => 'Interno',
                'aspectos' => [
                    [
                        'name'        => 'Inoperatividad y Mantenimiento Biomédico de Tomógrafos y Resonadores',
                        'environment' => 'Interno',
                        'description' => '<p><strong>Evidencia/Prensa:</strong> Equipos de imagenología (tomógrafos y resonadores) del Hospital Central e Ingavi fuera de servicio durante semanas por demoras en contratos de mantenimiento preventivo y trabas de importación de repuestos.</p>',
                        'tipo'        => 'Debilidad',
                        'desempeno'   => 0.32,
                        'inversion'   => 1.00,
                        'ocurrencia'  => 0.70,
                        'impacto'     => 0.80,
                        'causa_raiz'  => 'normativa',
                        'accion'      => 'Contratación de licitaciones plurianuales de mantenimiento integral directamente con fabricantes originales garantizando disponibilidad del 95%.',
                        'control'     => 'Reporte diario automatizado de disponibilidad técnica de equipos críticos de alta complejidad.',
                    ],
                    [
                        'name'        => 'Gestión de Contratos de Servicios de Limpieza, Higiene y Seguridad Privada',
                        'environment' => 'Interno',
                        'description' => '<p><strong>Evidencia/Auditoría:</strong> Reiteradas denuncias por deficiencias en la higiene hospitalaria en clínicas periféricas e irregularidades en la supervisión de empresas de seguridad privada.</p>',
                        'tipo'        => 'Debilidad',
                        'desempeno'   => 0.38,
                        'inversion'   => 1.00,
                        'ocurrencia'  => 0.70,
                        'impacto'     => 0.80,
                        'causa_raiz'  => 'operativa',
                        'accion'      => 'Revisión del pliego de bases y condiciones incorporando penalidades automáticas por incumplimiento en fiscalización y evaluación por usuarios.',
                        'control'     => 'Fiscalización sorpresa aleatoria por la Dirección de Servicios Generales y comités de epidemiología hospitalaria.',
                    ],
                ]
            ],

            // ── CATEGORÍA 6: Marco Regulatorio, Demografía y Coordinación Sectorial ──
            [
                'categoria' => 'Marco Regulatorio, Demografía y Coordinación Sectorial',
                'environment' => 'Externo',
                'aspectos' => [
                    [
                        'name'        => 'Alta Informalidad Laboral en Paraguay (60% de la Fuerza de Trabajo)',
                        'environment' => 'Externo',
                        'description' => '<p><strong>Evidencia/INE:</strong> Tasa de informalidad laboral superior al 60% en la PEA de Paraguay, restringiendo la masa de nuevos aportantes jóvenes para financiar las prestaciones de salud y pensiones.</p>',
                        'tipo'        => 'Amenaza',
                        'desempeno'   => 0.30,
                        'inversion'   => 1.00,
                        'ocurrencia'  => 0.90,
                        'impacto'     => 0.80,
                        'causa_raiz'  => 'normativa',
                        'accion'      => 'Impulsar proyectos de ley de formalización laboral flexible con alícuotas diferenciadas para MiPyMES e independientes.',
                        'control'     => 'Mesa de trabajo interinstitucional de formalización MTESS/DNIT/IPS.',
                    ],
                    [
                        'name'        => 'Transición Epidemiológica y Envejecimiento Poblacional',
                        'environment' => 'Externo',
                        'description' => '<p><strong>Evidencia/Salud Pública:</strong> Incremento acelerado de pacientes gerontológicos con múltiples enfermedades crónicas que triplican el costo promedio de atención con respecto a un cotizante joven.</p>',
                        'tipo'        => 'Amenaza',
                        'desempeno'   => 0.40,
                        'inversion'   => 1.00,
                        'ocurrencia'  => 0.90,
                        'impacto'     => 0.80,
                        'causa_raiz'  => 'estructural',
                        'accion'      => 'Fortalecer la red de Atención Primaria de la Salud (APS) con enfoque preventivo en enfermedades no transmisibles.',
                        'control'     => 'Ficha médica electrónica preventiva con programa de seguimiento domiciliario para adultos mayores.',
                    ],
                    [
                        'name'        => 'Convenios de Salud con MSPBS para Integración de Redes (RIISS)',
                        'environment' => 'Externo',
                        'description' => '<p><strong>Evidencia/Referencia IEA:</strong> Posibilidad de firmar acuerdos de complementariedad con el Ministerio de Salud en cabeceras departamentales para evitar duplicación de hospitales y optimizar camas quirúrgicas.</p>',
                        'tipo'        => 'Oportunidad',
                        'desempeno'   => 0.88,
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
                    'owner'       => 'Instituto de Previsión Social',
                    'environment' => $catData['environment'],
                    'parent_id'   => $root->id,
                ]);
            } else {
                $cat->update(['environment' => $catData['environment'], 'owner' => 'Instituto de Previsión Social']);
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
                        'owner'       => 'Instituto de Previsión Social',
                        'environment' => $aspData['environment'],
                        'description' => $aspData['description'],
                        'parent_id'   => $cat->id,
                    ]);
                } else {
                    $aspecto->update([
                        'owner'       => 'Instituto de Previsión Social',
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

                // Cargar FodaAnalisis en los perfiles objetivo
                foreach ($targetPerfilIds as $tPerfilId) {
                    $analisis = FodaAnalisis::where('perfil_id', $tPerfilId)
                        ->where('aspecto_id', $aspecto->id)
                        ->first();

                    $datosAnalisis = [
                        'user_id'                 => 1,
                        'perfil_id'               => $tPerfilId,
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
        }

        $this->command->info('¡Seeder FODA Ultra-Completo para IPS Paraguay cargado exitosamente!');
    }
}
