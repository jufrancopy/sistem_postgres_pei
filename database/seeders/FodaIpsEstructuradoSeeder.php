<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Admin\Planificacion\Foda\FodaPerfil;
use App\Admin\Planificacion\Foda\FodaModelo;
use App\Admin\Planificacion\Foda\FodaAnalisis;
use App\Admin\Planificacion\Foda\FodaCruceAmbiente;

class FodaIpsEstructuradoSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('Iniciando Seeder del Modelo FODA Arquitectura 14 Categorías para IPS Paraguay...');

        // Perfiles objetivo para poblar
        $targetPerfilIds = [
            'a0e99967-c8d5-4d27-adac-cf16fc9efbb3', // [DEMO] Análisis FODA — IPS 2025 (Grupal)
            '9a741ae1-95b2-4519-bb2c-6d4fdcc4b59e', // ANÁLISIS FODA IPS (Consolidado)
            'a24b1239-b102-410d-be8c-eabc06a5c079', // Nasim Barry (Individual)
        ];

        foreach ($targetPerfilIds as $pId) {
            $perfil = FodaPerfil::find($pId);
            if (!$perfil) {
                FodaPerfil::create([
                    'id'          => $pId,
                    'name'        => ($pId === 'a0e99967-c8d5-4d27-adac-cf16fc9efbb3') ? '[DEMO] Análisis FODA — IPS 2025' : 'ANÁLISIS FODA IPS',
                    'type'        => ($pId === 'a0e99967-c8d5-4d27-adac-cf16fc9efbb3') ? 'grupal' : 'consolidado',
                    'description' => 'Modelo FODA Arquitectura Completa con IEA y Fichas MECIP 2015 para IPS Paraguay',
                    'model_id'    => 1,
                ]);
            } else {
                $perfil->update(['model_id' => 1]);
            }
        }

        // Obtener o actualizar nodo Raíz
        $root = FodaModelo::find(1);
        if (!$root) {
            $root = FodaModelo::where('type', 'root')->first();
        }

        if ($root) {
            $root->update([
                'name'        => 'MODELO FODA ESTRUCTURADO — IPS PARAGUAY',
                'type'        => 'root',
                'owner'       => 'Instituto de Previsión Social',
                'description' => '<p>Arquitectura FODA Institucional de Alta Complejidad compuesta por 14 Categorías Especializadas y 30+ Aspectos evaluados con el Índice de Eficiencia de Activos (IEA) y Gestión de Riesgos MECIP 2015.</p>',
            ]);
        } else {
            $root = FodaModelo::create([
                'name'        => 'MODELO FODA ESTRUCTURADO — IPS PARAGUAY',
                'type'        => 'root',
                'owner'       => 'Instituto de Previsión Social',
                'description' => '<p>Arquitectura FODA Institucional de Alta Complejidad compuesta por 14 Categorías Especializadas y 30+ Aspectos evaluados con el Índice de Eficiencia de Activos (IEA) y Gestión de Riesgos MECIP 2015.</p>',
            ]);
        }

        // 14 Categorías Institucionales Especializadas de IPS Paraguay
        $validCatNames = [
            'Gestión de Salud y Servicios Médicos Especializados',
            'Talento Humano, Carga Horaria y Carrera Profesional',
            'Abastecimiento, Cadena de Suministro y Fármacos de Alto Costo',
            'Tercerización de Servicios Médicos y Contrataciones Complementarias',
            'Agendamiento, Sistemas de Información e Historia Clínica Digital',
            'Sostenibilidad Financiera y Fondo de Jubilaciones y Pensiones',
            'Recaudación, Evasión y Fiscalización de Mora Patronal',
            'Deuda del Estado y Relaciones Interinstitucionales',
            'Infraestructura Hospitalaria y Mantenimiento Biomédico',
            'Servicios Generales, Higiene Hospitalaria y Seguridad Privada',
            'Gobernanza, Transparencia, Control Interno y MECIP 2015',
            'Marco Macroeconómico, Demografía e Informalidad Laboral',
            'Atención al Asegurado, Imagen Institucional y Comunicación',
            'Salud Ocupacional, Medicina del Trabajo y Prevención de Riesgos'
        ];

        // Limpiar categorías antiguas no incluidas en la arquitectura de 14 categorías
        $oldCats = FodaModelo::where('parent_id', $root->id)
            ->whereNotIn('name', $validCatNames)
            ->get();

        foreach ($oldCats as $oldCat) {
            FodaModelo::where('parent_id', $oldCat->id)->delete();
            $oldCat->delete();
        }

        // Matriz Completa de 14 Categorías y Aspectos
        $estructura = [

            // 1. Gestión de Salud y Servicios Médicos Especializados
            [
                'categoria'   => 'Gestión de Salud y Servicios Médicos Especializados',
                'environment' => 'Interno',
                'description' => '<p>Evaluación de la capacidad resolutiva de la red sanitaria del IPS (Hospital Central, Ingavi, Benjamín Aceval, Luque, 12 de Junio y clínicas regionales). Mapeo de la disponibilidad de camas en Terapias Intensivas (UTI), quirófanos habilitados y especialidades de alta complejidad.</p>',
                'aspectos'    => [
                    [
                        'name'        => 'Capacidad Resolutiva de Terapias Intensivas (UTI Adultos, Pediátrica y Neonatal)',
                        'environment' => 'Interno',
                        'description' => '<p><strong>Evidencia/Auditoría:</strong> Alta ocupación de camas UTI (superando el 95% promedio en el Hospital Central e Ingavi), lo que genera diferimiento de cirugías complejas y derivaciones a sanatorios privados bajo amparos judiciales.</p>',
                        'tipo'        => 'Debilidad',
                        'desempeno'   => 0.32,
                        'inversion'   => 1.00,
                        'ocurrencia'  => 0.90,
                        'impacto'     => 0.80,
                        'causa_raiz'  => 'estructural',
                        'accion'      => 'Habilitación de nuevos pabellones de UTI descentralizados e incorporación de enfermeros especialistas en cuidados intensivos.',
                        'control'     => 'Monitoreo diario de camas libres y ocupadas vía sistema unificado de gestión de urgencias.',
                    ],
                    [
                        'name'        => 'Red de Unidades Hospitalarias de Trasplantes y Alta Complejidad',
                        'environment' => 'Interno',
                        'description' => '<p><strong>Evidencia/Referencia IEA:</strong> Capacidad instalada en el Hospital Central para la realización de trasplantes cardíacos, renales y médula ósea con equipos quirúrgicos de vanguardia.</p>',
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

            // 2. Talento Humano, Carga Horaria y Carrera Profesional
            [
                'categoria'   => 'Talento Humano, Carga Horaria y Carrera Profesional',
                'environment' => 'Interno',
                'description' => '<p>Análisis de las condiciones laborales del personal de blanco y administrativo. Aborda el impacto de la Ley de Carga Horaria de 12 horas, la brecha salarial con el sector privado, la fuga de médicos especialistas y el programa de residencias médicas.</p>',
                'aspectos'    => [
                    [
                        'name'        => 'Implementación de Carga Horaria Médica (Ley 12hs) y Cobertura de Guardias',
                        'environment' => 'Interno',
                        'description' => '<p><strong>Evidencia/Gremial:</strong> Aplicación progresiva de la reducción horaria a 12hs semanales por vínculo sin presupuesto para contratar reemplazos, dejando vacíos de atención en guardias de Urgencias y Anestesiología.</p>',
                        'tipo'        => 'Debilidad',
                        'desempeno'   => 0.30,
                        'inversion'   => 1.00,
                        'ocurrencia'  => 0.90,
                        'impacto'     => 0.80,
                        'causa_raiz'  => 'estructural',
                        'accion'      => 'Concursos públicos de méritos para contratación de médicos de contingencia y reorganización de turnos rotativos.',
                        'control'     => 'Control biométrico de marcación integrado a la auditoría de guardias presenciales.',
                    ],
                    [
                        'name'        => 'Fuga de Especialistas Médicos hacia el Sector Privado por Disparidad Salarial',
                        'environment' => 'Interno',
                        'description' => '<p><strong>Evidencia/Prensa:</strong> Emigración de anestesiólogos, neonatólogos y oncólogos por salarios no competitivos y falta de incentivos por radicación regional.</p>',
                        'tipo'        => 'Debilidad',
                        'desempeno'   => 0.35,
                        'inversion'   => 1.00,
                        'ocurrencia'  => 0.90,
                        'impacto'     => 0.80,
                        'causa_raiz'  => 'estructural',
                        'accion'      => 'Creación de un reglamento de incentivos por especialidad crítica y plus de radicación para el interior.',
                        'control'     => 'Evaluación semestral de la tasa de rotación y renuncia del personal de blanco.',
                    ],
                ]
            ],

            // 3. Abastecimiento, Cadena de Suministro y Fármacos de Alto Costo
            [
                'categoria'   => 'Abastecimiento, Cadena de Suministro y Fármacos de Alto Costo',
                'environment' => 'Interno',
                'description' => '<p>Diagnóstico de la gestión logística y licitatoria de fármacos del Cuadro Básico IPS. Mide el stock de insumos biológicos y oncológicos, pasivos con la industria farmacéutica y tiempos de adjudicación en la DNCP.</p>',
                'aspectos'    => [
                    [
                        'name'        => 'Deudas con Proveedores Farmacéuticos y Quiebres de Stock Oncológico',
                        'environment' => 'Interno',
                        'description' => '<p><strong>Evidencia/Auditoría:</strong> Deuda acumulada superior a USD 250 millones con Cifarma y Cimefor, que deriva en desabastecimientos de fármacos oncológicos e insumos de biopsia.</p>',
                        'tipo'        => 'Debilidad',
                        'desempeno'   => 0.25,
                        'inversion'   => 1.00,
                        'ocurrencia'  => 0.90,
                        'impacto'     => 0.80,
                        'causa_raiz'  => 'normativa',
                        'accion'      => 'Licitaciones con contratos abiertos multianuales y fideicomiso de pago garantizado para la industria nacional.',
                        'control'     => 'Auditoría semanal de quiebres de stock en el Parque Sanitario Central y farmacias hospitalarias.',
                    ],
                ]
            ],

            // 4. Tercerización de Servicios Médicos y Contrataciones Complementarias
            [
                'categoria'   => 'Tercerización de Servicios Médicos y Contrataciones Complementarias',
                'environment' => 'Interno',
                'description' => '<p>Evaluación de los servicios contratados a sanatorios privados (hemodiálisis, análisis de laboratorio especializado e imagenología externa). Análisis del costo-beneficio y fiscalización médica de las prestaciones tercerizadas.</p>',
                'aspectos'    => [
                    [
                        'name'        => 'Sobrecosto de Tercerización de Servicios de Diálisis y Laboratorios Externos',
                        'environment' => 'Interno',
                        'description' => '<p><strong>Evidencia/Prensa:</strong> Gasto elevado en derivaciones a empresas privadas por falta de sillones de diálisis e insumos de laboratorio en hospitales propios.</p>',
                        'tipo'        => 'Debilidad',
                        'desempeno'   => 0.38,
                        'inversion'   => 1.00,
                        'ocurrencia'  => 0.70,
                        'impacto'     => 0.80,
                        'causa_raiz'  => 'operativa',
                        'accion'      => 'Plan de inversión para la compra e instalación de 50 nuevos sillones de hemodiálisis en hospitales periféricos.',
                        'control'     => 'Auditoría médica de validación previa de cada paciente derivado a centros privados.',
                    ],
                ]
            ],

            // 5. Agendamiento, Sistemas de Información e Historia Clínica Digital
            [
                'categoria'   => 'Agendamiento, Sistemas de Información e Historia Clínica Digital',
                'environment' => 'Interno',
                'description' => '<p>Diagnóstico de la infraestructura tecnológica, estabilidad de servidores, Call Center, desarrollo del expediente clínico digital (SIH), receta electrónica y la aplicación web Mi IPS.</p>',
                'aspectos'    => [
                    [
                        'name'        => 'Saturación del Call Center y Caídas del Sistema de Información Hospitalaria (SIH)',
                        'environment' => 'Interno',
                        'description' => '<p><strong>Evidencia/Denuncias:</strong> Colapso telefónico del agendamiento y caídas del servidor del SIH que provocan esperas de 3 a 6 meses para conseguir turnos médicos.</p>',
                        'tipo'        => 'Debilidad',
                        'desempeno'   => 0.20,
                        'inversion'   => 1.00,
                        'ocurrencia'  => 0.90,
                        'impacto'     => 0.80,
                        'causa_raiz'  => 'tecnologica',
                        'accion'      => 'Implementar agendamiento omnicanal cloud por WhatsApp automatizado y App Mi IPS renovada.',
                        'control'     => 'Panel de monitoreo SLA del tiempo de respuesta telefónica y tasa de caídas de servidor.',
                    ],
                    [
                        'name'        => 'Avanzada de la Receta Electrónica e Historia Clínica Unificada',
                        'environment' => 'Externo',
                        'description' => '<p><strong>Evidencia/Referencia IEA:</strong> Avance en la digitalización de recetas para evitar el retiro fraudulento de fármacos y unificar las fichas clínicas con el MSPBS.',
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

            // 6. Sostenibilidad Financiera y Fondo de Jubilaciones y Pensiones
            [
                'categoria'   => 'Sostenibilidad Financiera y Fondo de Jubilaciones y Pensiones',
                'environment' => 'Interno',
                'description' => '<p>Análisis de las reservas técnicas del Fondo de Jubilaciones, cálculo actuarial a 2030-2038, fórmulas legales del haber jubilatorio y rendimiento de las inversiones financieras e inmobiliarias.</p>',
                'aspectos'    => [
                    [
                        'name'        => 'Riesgo Actuarial y Agotamiento de Reservas Técnicas del Fondo Jubilatorio',
                        'environment' => 'Externo',
                        'description' => '<p><strong>Evidencia/Actuarial:</strong> Informes técnicos alertan del agotamiento progresivo del superávit corriente del fondo jubilatorio hacia el 2030-2038 debido al aumento de expectativa de vida.</p>',
                        'tipo'        => 'Amenaza',
                        'desempeno'   => 0.30,
                        'inversion'   => 1.00,
                        'ocurrencia'  => 0.90,
                        'impacto'     => 0.80,
                        'causa_raiz'  => 'normativa',
                        'accion'      => 'Propuesta de reforma legal de la fórmula de cálculo sobre el promedio salarial de los últimos 10 años.',
                        'control'     => 'Monitoreo trimestral del comité de inversiones actuariales.',
                    ],
                ]
            ],

            // 7. Recaudación, Evasión y Fiscalización de Mora Patronal
            [
                'categoria'   => 'Recaudación, Evasión y Fiscalización de Mora Patronal',
                'environment' => 'Interno',
                'description' => '<p>Diagnóstico sobre el cobro de aportes obrero-patronales, fiscalización de empresas evasoras, subdeclaración de salarios cotizables y ejecución de certificados de deuda.</p>',
                'aspectos'    => [
                    [
                        'name'        => 'Mora Patronal Acumulada y Práctica de Subdeclaración de Salarios',
                        'environment' => 'Interno',
                        'description' => '<p><strong>Evidencia/Contraloría:</strong> Más de USD 400 millones en mora patronal acumulada y evación masiva de empresas privadas en el registro real de sueldos.</p>',
                        'tipo'        => 'Debilidad',
                        'desempeno'   => 0.28,
                        'inversion'   => 1.00,
                        'ocurrencia'  => 0.90,
                        'impacto'     => 0.80,
                        'causa_raiz'  => 'operativa',
                        'accion'      => 'Cruzamiento de bases de datos con la DNIT (Tributación) y fiscalizaciones conjuntas con el MTESS.',
                        'control'     => 'Bloqueo judicial automático y emisión de Certificados de Deuda ejecutivos.',
                    ],
                ]
            ],

            // 8. Deuda del Estado y Relaciones Interinstitucionales
            [
                'categoria'   => 'Deuda del Estado y Relaciones Interinstitucionales',
                'environment' => 'Externo',
                'description' => '<p>Análisis de los pasivos históricos acumulados por el Estado paraguayo con el Fondo de Salud del IPS por la atención dispensada a no asegurados y emergencias nacionales.</p>',
                'aspectos'    => [
                    [
                        'name'        => 'Deuda Consolidada del Estado Paraguayo con el Fondo de Salud IPS',
                        'environment' => 'Externo',
                        'description' => '<p><strong>Evidencia/Prensa:</strong> Acumulación de deuda histórica no saldada por el Ministerio de Economía por la atención médica brindada a no cotizantes.</p>',
                        'tipo'        => 'Amenaza',
                        'desempeno'   => 0.32,
                        'inversion'   => 1.00,
                        'ocurrencia'  => 0.90,
                        'impacto'     => 0.80,
                        'causa_raiz'  => 'normativa',
                        'accion'      => 'Mesa de negociación con el MEF para la emisión de bonos o transferencia de propiedades inmobiliarias del Estado.',
                        'control'     => 'Comisión intergubernamental de conciliación de cuentas de salud pública.',
                    ],
                ]
            ],

            // 9. Infraestructura Hospitalaria y Mantenimiento Biomédico
            [
                'categoria'   => 'Infraestructura Hospitalaria y Mantenimiento Biomédico',
                'environment' => 'Interno',
                'description' => '<p>Evaluación de la infraestructura física hospitalaria, obras en ejecución y contratos de mantenimiento técnico preventivo de tomógrafos, resonadores y aceleradores lineales.</p>',
                'aspectos'    => [
                    [
                        'name'        => 'Parada Prolongada de Tomógrafos y Resonadores por Demoras de Mantenimiento',
                        'environment' => 'Interno',
                        'description' => '<p><strong>Evidencia/Prensa:</strong> Inoperatividad recurrente de tomógrafos en el Hospital Central e Ingavi por burocracia licitatoria en servicios de mantenimiento oficial.</p>',
                        'tipo'        => 'Debilidad',
                        'desempeno'   => 0.32,
                        'inversion'   => 1.00,
                        'ocurrencia'  => 0.70,
                        'impacto'     => 0.80,
                        'causa_raiz'  => 'normativa',
                        'accion'      => 'Licitaciones plurianuales de mantenimiento integral directamente con fabricantes originales.',
                        'control'     => 'Panel diario de operatividad técnica de equipos biomédicos en tiempo real.',
                    ],
                ]
            ],

            // 10. Servicios Generales, Higiene Hospitalaria y Seguridad Privada
            [
                'categoria'   => 'Servicios Generales, Higiene Hospitalaria y Seguridad Privada',
                'environment' => 'Interno',
                'description' => '<p>Evaluación de los servicios auxiliares de limpieza, manejo de residuos patológicos, servicio de alimentación hospitalaria y vigilancia privada en toda la red asistencial.</p>',
                'aspectos'    => [
                    [
                        'name'        => 'Deficiencias en Servicios Tercerizados de Limpieza e Higiene Hospitalaria',
                        'environment' => 'Interno',
                        'description' => '<p><strong>Evidencia/Auditoría:</strong> Denuncias de usuarios sobre fallas de higiene en clínicas periféricas e irregularidades en la fiscalización de contratistas de limpieza.</p>',
                        'tipo'        => 'Debilidad',
                        'desempeno'   => 0.38,
                        'inversion'   => 1.00,
                        'ocurrencia'  => 0.70,
                        'impacto'     => 0.80,
                        'causa_raiz'  => 'operativa',
                        'accion'      => 'Incorporación de sanciones financieras automáticas por falta de desinfección y evaluación de satisfacción del asegurado.',
                        'control'     => 'Fiscalización aleatoria semanal por comités de epidemiología hospitalaria.',
                    ],
                ]
            ],

            // 11. Gobernanza, Transparencia, Control Interno y MECIP 2015
            [
                'categoria'   => 'Gobernanza, Transparencia, Control Interno y MECIP 2015',
                'environment' => 'Interno',
                'description' => '<p>Diagnóstico sobre el cumplimiento del Modelo Estándar de Control Interno (MECIP 2015), procesos de compras públicas, auditoría interna y portales de datos abiertos institucionales.</p>',
                'aspectos'    => [
                    [
                        'name'        => 'Implementación del Enfoque de Gestión de Riesgos MECIP 2015 en Unidades Administrativas',
                        'environment' => 'Interno',
                        'description' => '<p><strong>Evidencia/Referencia IEA:</strong> Adopción obligatoria de fichas de riesgo, causa raíz y controles preventivos en los procesos de compras y finanzas del IPS.</p>',
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

            // 12. Marco Macroeconómico, Demografía e Informalidad Laboral
            [
                'categoria'   => 'Marco Macroeconómico, Demografía e Informalidad Laboral',
                'environment' => 'Externo',
                'description' => '<p>Análisis de las variables externas de la economía paraguaya. Mide la tasa de informalidad laboral del 60%, el envejecimiento poblacional y la prevalencia de patologías crónicas.</p>',
                'aspectos'    => [
                    [
                        'name'        => 'Alta Tasa de Informalidad Laboral en Paraguay (60% de la PEA)',
                        'environment' => 'Externo',
                        'description' => '<p><strong>Evidencia/INE:</strong> Tasa de informalidad que supera el 60% en la masa trabajadora del país, impidiendo el crecimiento proporcional de los ingresos por cotizantes.',
                        'tipo'        => 'Amenaza',
                        'desempeno'   => 0.30,
                        'inversion'   => 1.00,
                        'ocurrencia'  => 0.90,
                        'impacto'     => 0.80,
                        'causa_raiz'  => 'normativa',
                        'accion'      => 'Regímenes de cotización flexible para trabajadores independientes y microempresas.',
                        'control'     => 'Mesa de trabajo interinstitucional MTESS/DNIT/IPS.',
                    ],
                    [
                        'name'        => 'Transición Epidemiológica y Aumento de Enfermedades Crónicas',
                        'environment' => 'Externo',
                        'description' => '<p><strong>Evidencia/MSPBS:</strong> Envejecimiento de la población de cotizantes con mayor consumo de tratamientos de oncología, cardiología y diabetes.',
                        'tipo'        => 'Amenaza',
                        'desempeno'   => 0.40,
                        'inversion'   => 1.00,
                        'ocurrencia'  => 0.90,
                        'impacto'     => 0.80,
                        'causa_raiz'  => 'estructural',
                        'accion'      => 'Reorientación presupuestaria hacia la Medicina Preventiva y Atención Primaria de la Salud.',
                        'control'     => 'Ficha médica electrónica con seguimiento domiciliario para pacientes gerontológicos.',
                    ],
                ]
            ],

            // 13. Atención al Asegurado, Imagen Institucional y Comunicación
            [
                'categoria'   => 'Atención al Asegurado, Imagen Institucional y Comunicación',
                'environment' => 'Interno',
                'description' => '<p>Evaluación de la percepción pública del seguro social, trato humanizado en ventanillas de atención, gestión de reclamos de los aportantes y campañas de información institucional.</p>',
                'aspectos'    => [
                    [
                        'name'        => 'Baja Calificación de Percepción Ciudadana sobre Trato y Tiempos de Atención',
                        'environment' => 'Interno',
                        'description' => '<p><strong>Evidencia/Encuestas:</strong> Descontento del asegurado por largas filas en ventanillas de farmacia y demoras en la expedición de reposos médicos.</p>',
                        'tipo'        => 'Debilidad',
                        'desempeno'   => 0.35,
                        'inversion'   => 1.00,
                        'ocurrencia'  => 0.90,
                        'impacto'     => 0.80,
                        'causa_raiz'  => 'operativa',
                        'accion'      => 'Capacitación obligatoria en trato humanizado al personal de atención al público y trámites 100% digitales.',
                        'control'     => 'Encuestas digitales de satisfacción inmediata tras cada atención en ventanilla.',
                    ],
                ]
            ],

            // 14. Salud Ocupacional, Medicina del Trabajo y Prevención de Riesgos
            [
                'categoria'   => 'Salud Ocupacional, Medicina del Trabajo y Prevención de Riesgos',
                'environment' => 'Interno',
                'description' => '<p>Análisis de las políticas de prevención de accidentes laborales, medicina del trabajo en empresas aportantes, fiscalizaciones ocupacionales y dictámenes de incapacidad laboral.</p>',
                'aspectos'    => [
                    [
                        'name'        => 'Programa de Prevención de Riesgos Laborales y Fiscalización Ocupacional en Empresas',
                        'environment' => 'Interno',
                        'description' => '<p><strong>Evidencia/Referencia IEA:</strong> Fiscalizaciones preventivas en industrias y obras de construcción para reducir accidentes de trabajo y subsidios por reposos largos.</p>',
                        'tipo'        => 'Fortaleza',
                        'desempeno'   => 0.82,
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
            // Buscar o crear categoría con su descripción rica HTML
            $cat = FodaModelo::where('name', $catData['categoria'])
                ->where('parent_id', $root->id)
                ->first();

            if (!$cat) {
                $cat = FodaModelo::create([
                    'name'        => $catData['categoria'],
                    'type'        => 'category',
                    'owner'       => 'Instituto de Previsión Social',
                    'environment' => $catData['environment'],
                    'description' => $catData['description'],
                    'parent_id'   => $root->id,
                ]);
            } else {
                $cat->update([
                    'environment' => $catData['environment'],
                    'owner'       => 'Instituto de Previsión Social',
                    'description' => $catData['description'],
                ]);
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

        // ── Población de Estrategias de Cruce (FO, DO, FA, DA) ──
        $estrategiasCruce = [
            // Estrategias FO (Fortalezas + Oportunidades)
            [
                'tipo'       => 'FO',
                'estrategia' => 'Aprovechar la avanzada en la receta electrónica e historia clínica unificada para optimizar la dispensación de fármacos y potenciar la Red de Trasplantes y Alta Complejidad en el Hospital Central.',
            ],
            [
                'tipo'       => 'FO',
                'estrategia' => 'Articular el Centro de Formación de Médicos Residentes e Internado del Hospital Central con convenios interinstitucionales del MSPBS (RIISS) para extender la cobertura médica especializada en el interior.',
            ],
            [
                'tipo'       => 'FO',
                'estrategia' => 'Utilizar la rentabilidad del portafolio financiero de reservas técnicas para financiar la transformación tecnológica cloud del agendamiento asistencial y App Mi IPS.',
            ],

            // Estrategias DO (Debilidades + Oportunidades)
            [
                'tipo'       => 'DO',
                'estrategia' => 'Desplegar plataformas omnicanal cloud por WhatsApp y kioscos digitales para eliminar la saturación del Call Center y estabilizar el agendamiento del SIH.',
            ],
            [
                'tipo'       => 'DO',
                'estrategia' => 'Establecer licitaciones con contratos abiertos multianuales e inventario automatizado en tiempo real para erradicar el quiebre de stock en medicamentos oncológicos.',
            ],
            [
                'tipo'       => 'DO',
                'estrategia' => 'Interconectar informáticamente las bases tributarias de la DNIT y MTESS para automatizar fiscalizaciones de mora patronal y cobro coactivo.',
            ],

            // Estrategias FA (Fortalezas + Amenazas)
            [
                'tipo'       => 'FA',
                'estrategia' => 'Apalancar la solidez de las reservas financieras e inmobiliarias para amortiguar el impacto del déficit actuarial proyectado a 2030-2038 y la presión epidemiológica gerontológica.',
            ],
            [
                'tipo'       => 'FA',
                'estrategia' => 'Utilizar la capacidad asistencial instalada y la red de formación médica para liderar las mesas de negociación con el Tesoro (MEF) en la conciliación de la deuda histórica del Estado.',
            ],
            [
                'tipo'       => 'FA',
                'estrategia' => 'Aprovechar la estructura de Medicina del Trabajo e Inspección Ocupacional para exigir la formalización gradual de trabajadores en sectores con alta tasa de informalidad.',
            ],

            // Estrategias DA (Debilidades + Amenazas)
            [
                'tipo'       => 'DA',
                'estrategia' => 'Impulsar una reforma legal integral de la Ley Orgánica del IPS para ajustar la fórmula de cálculo del haber jubilatorio sobre el promedio de los últimos 10 años ante la informalidad del 60%.',
            ],
            [
                'tipo'       => 'DA',
                'estrategia' => 'Contratar licitaciones plurianuales de mantenimiento biomédico preventivo directamente con fabricantes oficiales para evitar la inoperatividad de tomógrafos y los sobrecostos de derivación.',
            ],
            [
                'tipo'       => 'DA',
                'estrategia' => 'Establecer la carrera médica institucional con incentivos salariales por especialidad y desarraigo para frenar la fuga de talentos hacia el sector privado ante la Ley de Carga Horaria de 12hs.',
            ],
        ];

        foreach ($targetPerfilIds as $tPerfilId) {
            // Limpiar estrategias antiguas de este perfil
            FodaCruceAmbiente::where('perfil_id', $tPerfilId)->delete();

            foreach ($estrategiasCruce as $eData) {
                FodaCruceAmbiente::create([
                    'user_id'    => 1,
                    'perfil_id'  => $tPerfilId,
                    'tipo'       => $eData['tipo'],
                    'estrategia' => $eData['estrategia'],
                ]);
            }
        }

        $this->command->info('¡Seeder FODA Arquitectura 14 Categorías y Estrategias de Cruce para IPS Paraguay completado exitosamente!');
    }
}
