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
        $this->command->info('Iniciando Seeder del Modelo FODA Exhaustivo e Integral con Descripciones Ricas para IPS Paraguay...');

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
                    'description' => 'Modelo FODA Exhaustivo con IEA y Fichas MECIP 2015 para IPS Paraguay',
                    'model_id'    => 1,
                ]);
            } else {
                $perfil->update(['model_id' => 1]);
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

        // Nombres de categorías válidas para limpiar duplicados viejos
        $validCatNames = [
            'Talento Humano y Carga Horaria Médica',
            'Abastecimiento, Licitaciones y Cadena Farmacéutica',
            'Agendamiento, Tecnología e Historia Clínica Digital',
            'Fondo de Jubilaciones, Pensiones y Sostenibilidad Financiera',
            'Infraestructura Hospitalaria, Equipos y Servicios Generales',
            'Marco Regulatorio, Demografía y Coordinación Sectorial'
        ];

        // Limpiar categorías antiguas fuera de la lista válida bajo la raíz 1
        $oldCats = FodaModelo::where('parent_id', $root->id)
            ->whereNotIn('name', $validCatNames)
            ->get();

        foreach ($oldCats as $oldCat) {
            // Eliminar aspectos hijos
            FodaModelo::where('parent_id', $oldCat->id)->delete();
            $oldCat->delete();
        }

        // Matriz Exhaustiva de Categorías (con Descripción Rica) y Aspectos
        $estructura = [

            // ── CATEGORÍA 1: Talento Humano y Carga Horaria Médica ──
            [
                'categoria'   => 'Talento Humano y Carga Horaria Médica',
                'environment' => 'Interno',
                'description' => '<p>Evaluación estratégica de la gestión del capital humano de blanco en el IPS, enfocada en la implementación de la Ley de Carga Horaria de 12 horas por vínculo, la fuga de médicos especialistas hacia el sector privado por brechas salariales, la cobertura de guardias en Urgencias y UTI, y la formación médica de residentes en el Hospital Central.</p>',
                'aspectos'    => [
                    [
                        'name'        => 'Reducción de Carga Horaria Médica (Ley 12hs) y Cobertura de Guardias',
                        'environment' => 'Interno',
                        'description' => '<p><strong>Evidencia/Auditoría:</strong> Exigencia y aplicación progresiva de la carga horaria reducida de 12 horas semanales por vínculo para médicos sin contar con el presupuesto ni el plantel adicional necesario. Según reportes gremiales y denuncias periodísticas, esta reducción genera baches críticos de cobertura en las guardias de Urgencias, UTI y Anestesiología en el Hospital Central y clínicas periféricas, derivando en la suspensión de cirugías programadas y el diferimiento de citas asistenciales.</p>',
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
                        'description' => '<p><strong>Evidencia/Periodística:</strong> Emigración constante de médicos especialistas y subespecialistas (neonatólogos, cirujanos pediátricos, oncólogos) hacia el sector privado y el MSPBS debido a diferencias salariales significativas, falta de incentivos por desarraigo en hospitales del interior y sobrecarga asistencial en consultorios de alta demanda.</p>',
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
                        'description' => '<p><strong>Evidencia/Referencia IEA:</strong> El Hospital Central del IPS se mantiene como el mayor centro asistencial e internado de formación de médicos residentes del país en más de 35 especialidades médicas acreditadas por la ANEAES, constituyendo una fortaleza clave de capital intelectual institucional.</p>',
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
                'categoria'   => 'Abastecimiento, Licitaciones y Cadena Farmacéutica',
                'environment' => 'Interno',
                'description' => '<p>Análisis del ciclo logístico y financiero de insumos médicos y fármacos del Cuadro Básico IPS. Contempla el nivel de endeudamiento comercial con cámaras farmacéuticas (Cifarma/Cimefor), cuellos de botella en licitaciones públicas vía DNCP, desabastecimiento de medicamentos oncológicos/crónicos y el costo de servicios tercerizados.</p>',
                'aspectos'    => [
                    [
                        'name'        => 'Pasivos con Proveedores y Quiebre de Stock de Fármacos Oncológicos y Crónicos',
                        'environment' => 'Interno',
                        'description' => '<p><strong>Evidencia/Auditoría:</strong> Acumulación de pasivos comerciales con cámaras farmacéuticas (Cifarma/Cimefor) superiores a USD 250 millones, generando suspensiones parciales en la entrega de medicamentos biológicos e insumos de hemodiálisis. Los procesos licitatorios ante la DNCP registran demoras burocráticas de 6 a 9 meses, provocando quiebres recurrentes de stock en la farmacia central y periféricas.</p>',
                        'tipo'        => 'Debilidad',
                        'desempeno'   => 0.25,
                        'inversion'   => 1.00,
                        'ocurrencia'  => 0.90,
                        'impacto'     => 0.80,
                        'causa_raiz'  => 'normativa',
                        'accion'      => 'Establecer licitaciones con contratos abiertos plurianuales, fideicomiso de pagos garantizados e inventario automatizado conectando parques sanitarios con farmacias asistenciales.',
                        'control'     => 'Auditoría semanal de stock de medicamentos esenciales del Cuadro Básico IPS con alertas automáticas ante niveles mínimos.',
                    ],
                    [
                        'name'        => 'Alto Costo por Tercerización de Diálisis y Laboratorios Externos',
                        'environment' => 'Interno',
                        'description' => '<p><strong>Evidencia/Periodística:</strong> Insuficiencia de sillones de hemodiálisis propios y desabastecimiento de reactivos en laboratorios del IPS, obligando a suscribir contratos millonarios de tercerización de servicios con sanatorios privados con un elevado sobrecosto operativo por paciente derivado.</p>',
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
                        'description' => '<p><strong>Evidencia/Referencia IEA:</strong> Infraestructura frigorífica principal certificada con control digital continuo de temperatura para conservación de insumos biológicos, vacunas y sueros de alta sensibilidad térmica.</p>',
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
                'categoria'   => 'Agendamiento, Tecnología e Historia Clínica Digital',
                'environment' => 'Interno',
                'description' => '<p>Evaluación de los sistemas de información asistencial y canales de atención al usuario. Diagnóstico sobre la saturación del Call Center, la estabilidad del Sistema de Información Hospitalaria (SIH), los tiempos de espera para turnos médicos y el nivel de avance de la historia clínica y receta electrónica.</p>',
                'aspectos'    => [
                    [
                        'name'        => 'Saturación del Call Center y Fallas del Sistema SIH en Agendamiento',
                        'environment' => 'Interno',
                        'description' => '<p><strong>Evidencia/Denuncias:</strong> Reportes diarios de asegurados con tiempos de espera telefónica superiores a 45 minutos en la central telefónica y caídas recurrentes del Sistema de Información Hospitalaria (SIH). Esto deriva en demoras de 3 a 6 meses para conseguir turnos en especialidades como Traumatología, Neurología y Endocrinología.</p>',
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
                        'description' => '<p><strong>Evidencia/Referencia IEA:</strong> Avance gradual en la implementación de la receta electrónica e integración del expediente clínico digital con los sistemas del Metic y el MSPBS para eliminar el uso de fichas de papel y reducir fraudes en el retiro de medicamentos.</p>',
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
                'categoria'   => 'Fondo de Jubilaciones, Pensiones y Sostenibilidad Financiera',
                'environment' => 'Interno',
                'description' => '<p>Diagnóstico de la sostenibilidad técnica y financiera del Fondo de Jubilaciones y Pensiones. Incluye la medición del déficit actuarial proyectado a 2030-2038, los niveles de mora patronal y evasión de aportes obrero-patronales, la consolidación de la deuda histórica del Estado paraguayo con el IPS y la rentabilidad del portafolio inmobiliario y financiero.</p>',
                'aspectos'    => [
                    [
                        'name'        => 'Déficit Actuarial y Riesgo en la Reserva del Fondo de Jubilaciones',
                        'environment' => 'Externo',
                        'description' => '<p><strong>Evidencia/Informes Actuariales:</strong> Diversos estudios actuariales advierten el agotamiento paulatino del superávit corriente de las reservas técnicas entre el 2030 y 2038 debido al aumento continuo de la expectativa de vida de los jubilados, el envejecimiento de la masa de cotizantes y la rigidez de la fórmula legal de cálculo de haberes jubilatorios.</p>',
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
                        'description' => '<p><strong>Evidencia/Auditoría Contraloría:</strong> Acumulación de mora patronal histórica estimada en más de USD 400 millones por parte de empresas privadas y reparticiones públicas. Asimismo, persiste la práctica de subdeclaración de salarios cotizables ante el seguro social para aminorar la carga del aporte obrero-patronal.</p>',
                        'tipo'        => 'Debilidad',
                        'desempeno'   => 0.28,
                        'inversion'   => 1.00,
                        'ocurrencia'  => 0.90,
                        'impacto'     => 0.80,
                        'causa_raiz'  => 'operativa',
                        'accion'      => 'Cruzamiento informático automático de datos con la DNIT (Tributación) y el Ministerio de Trabajo (MTESS) con bloqueos judiciales inmediatos.',
                        'control'     => 'Sistema de emisión de Certificados de Deuda automatizados con fuerza ejecutiva penal para cobro coactivo.',
                    ],
                    [
                        'name'        => 'Deuda Histórica del Estado Paraguayo con el Fondo de Salud del IPS',
                        'environment' => 'Externo',
                        'description' => '<p><strong>Evidencia/Prensa:</strong> Acumulación de deuda histórica no saldada por el Estado paraguayo derivada de las prestaciones de salud brindadas por la red del IPS a ciudadanos no cotizantes durante emergencias sanitarias y mandatos constitucionales de salud pública.</p>',
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
                        'description' => '<p><strong>Evidencia/Referencia IEA:</strong> Inversión en CDA bancarios del sistema financiero nacional regulado generando rendimientos financieros estables que ayudan a amortiguar el impacto inflacionario sobre los fondos líquidos de reserva.</p>',
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
                'categoria'   => 'Infraestructura Hospitalaria, Equipos y Servicios Generales',
                'environment' => 'Interno',
                'description' => '<p>Análisis de la capacidad instalada y la operatividad de activos físicos de alta complejidad. Diagnóstico sobre la disponibilidad técnica de tomógrafos, resonadores y aceleradores lineales, el cumplimiento de contratos de mantenimiento biomédico preventivo y la calidad de los servicios tercerizados de higiene hospitalaria y seguridad.</p>',
                'aspectos'    => [
                    [
                        'name'        => 'Inoperatividad y Mantenimiento Biomédico de Tomógrafos y Resonadores',
                        'environment' => 'Interno',
                        'description' => '<p><strong>Evidencia/Prensa:</strong> Equipos biomédicos de alta complejidad (tomógrafos, resonadores magnéticos y aceleradores lineales) del Hospital Central e Ingavi permanecen inoperativos por periodos prolongados debido a burocracia administrativa en las licitaciones de mantenimiento y retrasos en la importación de repuestos oficiales.</p>',
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
                        'description' => '<p><strong>Evidencia/Auditoría:</strong> Reiteradas quejas de usuarios sobre deficiencias en la higiene hospitalaria de sanitarios y salas de clínicas periféricas, sumadas a auditorías con hallazgos sobre la falta de supervisión efectiva en el cumplimiento de los contratos de seguridad privada.</p>',
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
                'categoria'   => 'Marco Regulatorio, Demografía y Coordinación Sectorial',
                'environment' => 'Externo',
                'description' => '<p>Evaluación del entorno macroeconómico, normativo y demográfico que condiciona al seguro social. Contempla el impacto de una tasa de informalidad laboral del 60% en la PEA, la transición epidemiológica hacia patologías crónicas no transmisibles en adultos mayores y las oportunidades de articulación en red con el MSPBS (RIISS).</p>',
                'aspectos'    => [
                    [
                        'name'        => 'Alta Informalidad Laboral en Paraguay (60% de la Fuerza de Trabajo)',
                        'environment' => 'Externo',
                        'description' => '<p><strong>Evidencia/INE:</strong> Tasa de informalidad laboral persistente por encima del 60% en la Población Económicamente Activa (PEA) de Paraguay, lo que restringe severamente la incorporación de nuevos cotizantes jóvenes para sostener los fondos de salud y jubilaciones.</p>',
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
                        'description' => '<p><strong>Evidencia/Salud Pública:</strong> Aumento acelerado de pacientes gerontológicos con múltiples enfermedades crónicas no transmisibles (hipertensión, diabetes, afecciones cardiovasculares y renales) que triplican el costo promedio de atención con respecto a un cotizante joven.</p>',
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
                        'description' => '<p><strong>Evidencia/Referencia IEA:</strong> Oportunidad de consolidar acuerdos de complementariedad de servicios asistenciales con el Ministerio de Salud Pública en cabeceras departamentales para evitar la duplicación de hospitales y optimizar la capacidad de quirófanos y camajes.</p>',
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

        $this->command->info('¡Seeder FODA Ultra-Completo con Descripciones Ricas para IPS Paraguay completado exitosamente!');
    }
}
