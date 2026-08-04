<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\GroqService;

class ReflexionController extends Controller
{
    /**
     * Banco de Valores del Código de Ética Institucional del IPS
     */
    protected array $valoresIPS = [
        [
            'tipo'        => 'etica_ips',
            'categoria'   => 'Código de Ética IPS · Valor 1',
            'titulo'      => 'Honestidad',
            'frase'       => 'Realizamos nuestro trabajo con absoluta honradez, rectitud de ánimo, dignidad y decencia.',
            'definicion'  => 'Una persona honesta busca con ahínco lo recto, lo honrado, lo razonable y lo justo. No pretende jamás aprovecharse de la confianza, la inocencia o la ignorancia de otros.',
            'declaracion' => 'Realizamos nuestro trabajo con absoluta honradez, rectitud de ánimo, dignidad y decencia; cuidamos y utilizamos los bienes y recursos que administramos exclusivamente para el desempeño de la Función Pública y privilegiamos en todas las actuaciones el interés general de nuestros asegurados.',
            'icono'       => 'fa-balance-scale',
            'color'       => '#1e3a8a',
        ],
        [
            'tipo'        => 'etica_ips',
            'categoria'   => 'Código de Ética IPS · Valor 2',
            'titulo'      => 'Integridad',
            'frase'       => 'Que en cualquier circunstancia nuestra conducta genere y fortaclezca la confianza.',
            'definicion'  => 'La persona íntegra tiene un comportamiento probo, recto e intachable, sigue sus principios éticos en lo que dice y en lo que hace.',
            'declaracion' => 'Desempeñamos nuestras funciones con respeto, prudencia, integridad, solidaridad, honestidad, decencia, seriedad, moralidad, justicia y rectitud, poniendo en la ejecución de nuestras labores toda nuestra capacidad, conocimiento y experiencia laboral; procuramos que en cualquier circunstancia nuestra conducta genere y fortalezca la confianza en nosotros y en la Institución.',
            'icono'       => 'fa-shield-alt',
            'color'       => '#0d9488',
        ],
        [
            'tipo'        => 'etica_ips',
            'categoria'   => 'Código de Ética IPS · Valor 3',
            'titulo'      => 'Responsabilidad',
            'frase'       => 'Actuamos con un claro concepto del deber y damos respuesta a los compromisos con celeridad, calidad y eficiencia.',
            'definicion'  => 'Una persona responsable es capaz de reconocer y hacerse cargo de las consecuencias de sus propias acciones y dar respuesta a todos los compromisos asumidos.',
            'declaracion' => 'Es nuestra obligación conocer y respetar la Constitución Nacional, las leyes y reglamentos vigentes; actuamos con un claro concepto del deber; reconocemos y asumimos las consecuencias de nuestras acciones u omisiones relativas al ejercicio de nuestra función; asumimos nuestro deber de superación personal y profesional... damos respuesta a los compromisos asumidos con celeridad, calidad y eficiencia y utilizamos el tiempo laboral responsablemente.',
            'icono'       => 'fa-tasks',
            'color'       => '#d97706',
        ],
        [
            'tipo'        => 'etica_ips',
            'categoria'   => 'Código de Ética IPS · Valor 4',
            'titulo'      => 'Respeto',
            'frase'       => 'Reconocemos el derecho de los demás a tener su propia opinión, promoviendo un clima de armonía laboral.',
            'definicion'  => 'Una persona respetuosa reconoce y acepta las diferencias y particularidades del otro, se comporta con rectitud y decoro.',
            'declaracion' => 'Reconocemos el derecho de los demás a tener su propia opinión y distintas formas de ser; sostenemos que las diferencias no son razones que justifiquen un trato discriminatorio ni preferencial; somos justos, respetuosos y amables en nuestra relación con los usuarios, con nuestros superiores, con nuestros colaboradores y con nuestros compañeros promoviendo un clima de armonía laboral.',
            'icono'       => 'fa-handshake',
            'color'       => '#4f46e5',
        ],
        [
            'tipo'        => 'etica_ips',
            'categoria'   => 'Código de Ética IPS · Valor 5',
            'titulo'      => 'Solidaridad',
            'frase'       => 'Promovemos el trabajo en equipo y apoyamos a nuestros compañeros en su desarrollo profesional.',
            'definicion'  => 'Una persona solidaria siempre está dispuesta a ayudar y servir a los demás.',
            'declaracion' => 'Colaboramos con los asegurados y estamos siempre dispuestos para ayudarles en la solución de sus necesidades que sean competencia de la misión del IPS; promovemos el trabajo en equipo y apoyamos a nuestros compañeros en su realización profesional; nos apoyamos mutuamente para mejorar las condiciones de vida laboral y prestar un buen servicio.',
            'icono'       => 'fa-heart',
            'color'       => '#e11d48',
        ],
        [
            'tipo'        => 'etica_ips',
            'categoria'   => 'Código de Ética IPS · Valor 6',
            'titulo'      => 'Vocación de Servicio',
            'frase'       => 'Somos serviciales, ayudamos espontáneamente y adoptamos una actitud permanente de colaboración.',
            'definicion'  => 'Una persona con vocación de servicio, da siempre un trato amable al ciudadano y pone al servicio de los demás lo mejor de sí mismo.',
            'declaracion' => 'Somos serviciales, ayudamos espontáneamente y adoptamos una actitud permanente de colaboración; atendemos los requerimientos de todos los usuarios del IPS con corrección y cortesía y focalizamos nuestras actuaciones hacia el bienestar de los demás.',
            'icono'       => 'fa-hands-helping',
            'color'       => '#0284c7',
        ],
        [
            'tipo'        => 'etica_ips',
            'categoria'   => 'Código de Ética IPS · Valor 7',
            'titulo'      => 'Transparencia',
            'frase'       => 'Actuamos siempre en forma clara, transmitiendo seguridad y credibilidad en cada gestión.',
            'definicion'  => 'Una persona transparente actúa de manera abierta, visible, permitiendo a los demás conocer la gestión realizada y la razón por la que actúa en uno y otro sentido.',
            'declaracion' => 'Acorde a la normativa legal del Instituto, brindamos a la ciudadanía información oportuna, completa y veraz sobre la forma de utilización de los recursos y bienes públicos que administramos y los resultados de nuestra gestión... actuamos siempre en forma clara transmitiendo seguridad y credibilidad en nosotros y en la Institución; repudiamos, combatimos y denunciamos toda forma de corrupción.',
            'icono'       => 'fa-eye',
            'color'       => '#16a34a',
        ],
        [
            'tipo'        => 'etica_ips',
            'categoria'   => 'Código de Ética IPS · Valor 8',
            'titulo'      => 'Eficiencia',
            'frase'       => 'Desempeñamos nuestras tareas con disciplina, diligencia y excelencia para alcanzar los objetivos institucionales.',
            'definicion'  => 'Una persona eficiente se esfuerza por alcanzar los objetivos propuestos con la máxima calidad posible y con la mayor economía de recursos.',
            'declaracion' => 'Desempeñamos las funciones propias del cargo, con elevada moral, profesionalismo, vocación, disciplina, diligencia, oportunidad y eficiencia para dignificar la función pública y mejorar la calidad de los servicios que prestamos... aportamos iniciativas para encontrar y aplicar las formas más eficientes y económicas de realizar las tareas para el logro de los objetivos institucionales.',
            'icono'       => 'fa-chart-line',
            'color'       => '#9333ea',
        ],
    ];

    /**
     * Citas Filosóficas sobre el Servicio, Trabajo y Dignidad
     */
    protected array $frasesFilosoficas = [
        [
            'tipo'        => 'filosofia',
            'categoria'   => 'Sabiduría Universal sobre el Trabajo',
            'titulo'      => 'Aristóteles',
            'frase'       => 'El placer en el trabajo pone la perfección en la obra. Servir a la comunidad es la forma más elevada de virtud.',
            'definicion'  => 'Para los filósofos clásicos, la dignidad del trabajo no reside únicamente en el producto obtenido, sino en la excelencia moral del servidor que realiza su tarea pensando en el bien común.',
            'declaracion' => '«Somos lo que hacemos día a día. De modo que la excelencia no es un acto, sino un hábito.» — Aristóteles',
            'icono'       => 'fa-university',
            'color'       => '#475569',
        ],
        [
            'tipo'        => 'filosofia',
            'categoria'   => 'Sabiduría Estoica',
            'titulo'      => 'Marco Aurelio',
            'frase'       => 'Al levantarte por la mañana, piensa en qué privilegio es estar vivo, pensar, disfrutar, amar y cumplir con tu deber.',
            'definicion'  => 'La filosofía estoica nos enseña que cada jornada de trabajo público es una oportunidad para honrar nuestra vocación y contribuir al bienestar de nuestros semejantes.',
            'declaracion' => '«¿Te levantas por la mañana para trabajar como un ser humano o para esconderte bajo las cobijas? No fuiste hecho para la comodidad, sino para actuar y servir.» — Marco Aurelio',
            'icono'       => 'fa-feather-alt',
            'color'       => '#334155',
        ],
        [
            'tipo'        => 'filosofia',
            'categoria'   => 'Vocación de Servicio Humanitaria',
            'titulo'      => 'Albert Schweitzer',
            'frase'       => 'Los únicos entre vosotros que serán verdaderamente felices serán los que hayan buscado y encontrado cómo servir.',
            'definicion'  => 'El Premio Nobel de la Paz nos recuerda que el verdadero sentido del servicio público es la compasión activa y la entrega genuina a quien necesita nuestra ayuda.',
            'declaracion' => '«Da siempre un ejemplo de entrega. No sabes a cuántas personas inspiras en silencio cada día al hacer bien tu trabajo.» — Albert Schweitzer',
            'icono'       => 'fa-hand-holding-heart',
            'color'       => '#059669',
        ],
        [
            'tipo'        => 'filosofia',
            'categoria'   => 'Dignidad e Igualdad del Trabajo',
            'titulo'      => 'Martin Luther King Jr.',
            'frase'       => 'Si a un hombre le corresponde ser servidor, debería realizar su trabajo como Miguel Ángel pintaba cuadros o Beethoven compuso música.',
            'definicion'  => 'Todo trabajo hecho con amor y dignidad técnica es un arte sagrado que eleva a la persona y engrandece a la institución pública.',
            'declaracion' => '«Todo trabajo que eleva la humanidad tiene dignidad e importancia y debe ser emprendido con minuciosa excelencia.» — Martin Luther King Jr.',
            'icono'       => 'fa-quote-left',
            'color'       => '#b45309',
        ],
        [
            'tipo'        => 'filosofia',
            'categoria'   => 'Amor por lo que Hacemos',
            'titulo'      => 'Khalil Gibran',
            'frase'       => 'El trabajo es el amor hecho visible. Trabajar con amor es tejer la tela con los hilos que salen de tu propio corazón.',
            'definicion'  => 'Cuando ponemos pasión en cada atención, trámite o proceso, transformamos una simple tarea administrativa en un acto trascendente de entrega humana.',
            'declaracion' => '«Y si no puedes trabajar con amor sino solo con desgana, mejor es que dejes tu tarea y te sientes a la puerta del templo a recibir limosna de los que trabajan con alegría.» — Khalil Gibran',
            'icono'       => 'fa-heart',
            'color'       => '#be123c',
        ],
        [
            'tipo'        => 'filosofia',
            'categoria'   => 'Sentido del Deber y la Vida',
            'titulo'      => 'Viktor Frankl',
            'frase'       => 'El ser humano se realiza en la medida en que se compromete al servicio de los demás y al cumplimiento de su deber.',
            'definicion'  => 'El trabajo público cobra su verdadero significado cuando comprendemos que cada expediente, cada paciente o asegurado es una vida humana bajo nuestra responsabilidad.',
            'declaracion' => '«La vida nunca se vuelve insoportable por las circunstancias, sino solo por la falta de significado y propósito.» — Viktor Frankl',
            'icono'       => 'fa-sun',
            'color'       => '#65a30d',
        ],
    ];

    /**
     * Obtiene la reflexión del día (alternando entre valores IPS y filósofos según la fecha del año)
     */
    public function obtenerReflexionDiaria(Request $request)
    {
        $todas = array_merge($this->valoresIPS, $this->frasesFilosoficas);
        
        // Si pide aleatoria manualmente
        if ($request->has('random') && $request->random == '1') {
            $item = $todas[array_rand($todas)];
        } else {
            // Seleccionar basada en el día del año para consistencia diaria
            $dayOfYear = (int) date('z');
            $index = $dayOfYear % count($todas);
            $item = $todas[$index];
        }

        return response()->json([
            'success' => true,
            'data'    => $item
        ]);
    }

    /**
     * Genera una reflexión inspiradora dinámica usando Groq AI (Llama 3.3 70B)
     */
    public function generarConGroq(Request $request)
    {
        try {
            $valorNombre = $request->input('valor', 'Vocación de Servicio');
            
            $prompt = "Eres un inspirador sabio y consejero de ética pública del Instituto de Previsión Social (IPS). "
                    . "Genera una reflexión emotiva y motivadora para la jornada laboral de un funcionario público. "
                    . "La reflexión debe centrarse en el valor de '{$valorNombre}', la dignidad del trabajo bien hecho y el amor al servicio al asegurado/ciudadano. "
                    . "Estructura la respuesta estrictamente en este formato JSON válido (sin marcas markdown de código):\n"
                    . "{\n"
                    . '  "titulo": "Reflexión IA sobre ' . $valorNombre . '",' . "\n"
                    . '  "categoria": "Inspiración Generada por IA (Groq)",' . "\n"
                    . '  "frase": "Una frase corta muy potente e inspiradora para poner de encabezado (máx 15 palabras).",' . "\n"
                    . '  "definicion": "Una reflexión de 2 o 3 oraciones profundas sobre por qué nuestro trabajo de hoy honra a la patria y al ciudadano.",' . "\n"
                    . '  "declaracion": "Un pensamiento motivador final para arrancar el día con orgullo e integridad."' . "\n"
                    . "}";

            $groq = new GroqService();
            $respuestaTexto = $groq->generarTextoLibre($prompt, 800);

            // Limpiar posibles bloques ```json ```
            $respuestaTexto = preg_replace('/^```json\s*/i', '', $respuestaTexto);
            $respuestaTexto = preg_replace('/^```\s*/i', '', $respuestaTexto);
            $respuestaTexto = preg_replace('/\s*```$/i', '', $respuestaTexto);

            $data = json_decode($respuestaTexto, true);

            if (!$data || !isset($data['frase'])) {
                // Fallback si la respuesta no es un JSON perfecto
                $data = [
                    'tipo'        => 'ia_groq',
                    'categoria'   => 'Inspiración IA (Groq)',
                    'titulo'      => 'Reflexión sobre ' . $valorNombre,
                    'frase'       => 'Cada pequeña acción de servicio que realizamos hoy construye un Paraguay mejor.',
                    'definicion'  => 'El trabajo en el sector público es un privilegio de trascendencia. Al atender a cada persona con dignidad, honramos nuestra misión e inspiramos a nuestra comunidad.',
                    'declaracion' => 'Que la excelencia, el amor y la probidad guíen cada decisión de tu jornada.',
                    'icono'       => 'fa-robot',
                    'color'       => '#7c3aed',
                ];
            } else {
                $data['tipo']  = 'ia_groq';
                $data['icono'] = 'fa-robot';
                $data['color'] = '#7c3aed';
            }

            return response()->json([
                'success' => true,
                'data'    => $data
            ]);

        } catch (\Exception $e) {
            // Si falla la API o no hay conexión, devolver un fallback inspirador
            $valorNombre = $request->input('valor', 'Vocación de Servicio');
            return response()->json([
                'success' => true,
                'data'    => [
                    'tipo'        => 'ia_groq',
                    'categoria'   => 'Inspiración Diaria (IPS)',
                    'titulo'      => 'Reflexión sobre ' . $valorNombre,
                    'frase'       => 'Servir con amor y rectitud transforma el trabajo en una obra sagrada.',
                    'definicion'  => 'Cada tarea asignada es un compromiso con los asegurados. Ponemos nuestro conocimiento y corazón al servicio del bien común.',
                    'declaracion' => '«El trabajo hecho con honestidad e integridad es la semilla del progreso institucional.»',
                    'icono'       => 'fa-lightbulb',
                    'color'       => '#1e3a8a',
                ]
            ]);
        }
    }
}
