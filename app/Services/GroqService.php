<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class GroqService
{
    protected Client $client;
    protected string $apiKey;
    protected string $model;

    public function __construct()
    {
        $this->client = new Client(['timeout' => 30]);
        $this->apiKey = env('GROQ_API_KEY');
        $this->model  = env('GROQ_MODEL', 'llama-3.3-70b-versatile');
    }

    public function generarTextoLibre(string $prompt, int $maxTokens = 1500): string
    {
        try {
            $response = $this->client->post('https://api.groq.com/openai/v1/chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type'  => 'application/json',
                ],
                'json' => [
                    'model'       => $this->model,
                    'messages'    => [['role' => 'user', 'content' => $prompt]],
                    'temperature' => 0.7,
                    'max_tokens'  => $maxTokens,
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            return trim($data['choices'][0]['message']['content'] ?? '');

        } catch (RequestException $e) {
            $body = $e->hasResponse()
                ? json_decode($e->getResponse()->getBody()->getContents(), true)
                : [];
            throw new \Exception($body['error']['message'] ?? 'Error al conectar con Groq API.');
        }
    }

    /**
     * Genera una estrategia FODA usando Groq / Llama 3.
     *
     * @param string $tipo     FO | DO | FA | DA
     * @param array  $grupo1   Aspectos del primer grupo (Fortalezas o Debilidades)
     * @param array  $grupo2   Aspectos del segundo grupo (Oportunidades o Amenazas)
     * @param string $contexto Nombre del perfil / institución
     */
    public function generarEstrategiaFoda(
        string $tipo,
        array  $grupo1,
        array  $grupo2,
        string $contexto = 'IPS Paraguay'
    ): string {
        $nombres = [
            'FO' => ['Ofensiva',        'Fortalezas',  'Oportunidades'],
            'DO' => ['Reorientación',   'Debilidades', 'Oportunidades'],
            'FA' => ['Defensiva',       'Fortalezas',  'Amenazas'],
            'DA' => ['Supervivencia',   'Debilidades', 'Amenazas'],
        ];

        [$tipoNombre, $label1, $label2] = $nombres[$tipo];

        $lista1 = collect($grupo1)->map(fn($a) => "- {$a['prefix']}{$a['id']}: {$a['name']}")->implode("\n");
        $lista2 = collect($grupo2)->map(fn($a) => "- {$a['prefix']}{$a['id']}: {$a['name']}")->implode("\n");

        $prompt = <<<PROMPT
Eres un consultor estratégico experto en planificación institucional bajo la metodología MECIP 2015 y el marco FODA.

Contexto institucional: {$contexto}

Tu tarea es redactar UNA estrategia {$tipoNombre} ({$tipo}) concisa, accionable y profesional, que aproveche o mitigue los siguientes aspectos:

{$label1}:
{$lista1}

{$label2}:
{$lista2}

Reglas:
- La estrategia debe ser una sola oración clara (máximo 2 oraciones).
- Debe referenciar implícitamente los aspectos seleccionados.
- Usa lenguaje institucional formal, orientado a resultados.
- No uses frases genéricas como "aprovechar las fortalezas". Sé específico.
- Responde SOLO con el texto de la estrategia, sin explicaciones ni prefijos.
PROMPT;

        try {
            $response = $this->client->post('https://api.groq.com/openai/v1/chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type'  => 'application/json',
                ],
                'json' => [
                    'model'       => $this->model,
                    'messages'    => [
                        ['role' => 'user', 'content' => $prompt],
                    ],
                    'temperature' => 0.7,
                    'max_tokens'  => 200,
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            return trim($data['choices'][0]['message']['content'] ?? 'No se pudo generar la estrategia.');

        } catch (RequestException $e) {
            $body = $e->hasResponse()
                ? json_decode($e->getResponse()->getBody()->getContents(), true)
                : [];
            throw new \Exception($body['error']['message'] ?? 'Error al conectar con Groq API.');
        }
    }

    /**
     * Genera Acción de Mejora y Control Preventivo MECIP para una Debilidad/Amenaza.
     */
    public function generarMecip(
        string $aspecto,
        string $tipo,
        string $causaRaiz,
        float  $ocurrencia,
        float  $impacto,
        string $contexto = 'IPS Paraguay'
    ): array {
        $causasLabel = [
            'operativa'   => 'Operativa (exceso de carga administrativa)',
            'estructural' => 'Estructural (falta de perfiles técnicos)',
            'tecnologica' => 'Tecnológica (ausencia de herramientas)',
            'normativa'   => 'Normativa (vacío regulatorio)',
            'otra'        => 'Otra',
        ];
        $causaTexto  = $causasLabel[$causaRaiz] ?? $causaRaiz;
        $nivelRiesgo = round($ocurrencia * $impacto, 4);
        $nivelTexto  = $nivelRiesgo >= 0.36 ? 'CRÍTICO' : ($nivelRiesgo >= 0.09 ? 'ALTO' : 'MODERADO');

        $prompt = <<<PROMPT
Eres un consultor experto en control interno bajo la metodología MECIP 2015 del Paraguay.

Contexto institucional: {$contexto}

Se ha identificado la siguiente situación de riesgo:
- Aspecto analizado: "{$aspecto}"
- Clasificación FODA: {$tipo}
- Causa raíz: {$causaTexto}
- Nivel de riesgo: {$nivelTexto} (Ocurrencia × Impacto = {$nivelRiesgo})

Tu tarea es generar DOS respuestas:

1. ACCIÓN DE MEJORA: Acción concreta, accionable y medible para transformar esta debilidad/amenaza. Máximo 2 oraciones. Específica para el IPS Paraguay.

2. CONTROL PREVENTIVO: Medida de control interno para evitar que el riesgo se materialice, alineada con el componente de Evaluación de Control del MECIP. Máximo 2 oraciones.

Responde ÚNICAMENTE con este JSON (sin texto adicional):
{"accion_mejora": "...", "control_preventivo": "..."}
PROMPT;

        try {
            $response = $this->client->post('https://api.groq.com/openai/v1/chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type'  => 'application/json',
                ],
                'json' => [
                    'model'       => $this->model,
                    'messages'    => [['role' => 'user', 'content' => $prompt]],
                    'temperature' => 0.6,
                    'max_tokens'  => 300,
                ],
            ]);

            $data    = json_decode($response->getBody()->getContents(), true);
            $content = trim($data['choices'][0]['message']['content'] ?? '{}');

            preg_match('/\{.*\}/s', $content, $matches);
            $result = json_decode($matches[0] ?? '{}', true);

            return [
                'accion_mejora'      => $result['accion_mejora']      ?? 'No se pudo generar.',
                'control_preventivo' => $result['control_preventivo'] ?? 'No se pudo generar.',
            ];

        } catch (RequestException $e) {
            $body = $e->hasResponse()
                ? json_decode($e->getResponse()->getBody()->getContents(), true)
                : [];
            throw new \Exception($body['error']['message'] ?? 'Error al conectar con Groq API.');
        }
    }

    /**
     * Mejorar la redacción de un borrador según la metodología SMART y tono institucional IPS.
     */
    public function redactarSmart(string $texto, string $tipo = 'Objetivo'): string
    {
        $prompt = <<<PROMPT
Eres un experto consultor internacional en planificación estratégica pública y metodología SMART para el Instituto de Previsión Social (IPS) de Paraguay y el Plan Nacional de Desarrollo PND 2050.

Tu tarea es recibir el siguiente borrador o texto simple de un {$tipo} y reescribirlo en un texto claro, técnico, formal, medible y orientado a resultados de salud y gestión pública en el Paraguay.

Borrador: "{$texto}"

Reglas:
- Responde ÚNICAMENTE con el texto de la propuesta mejorada.
- No agregues introducciones, saludos ni explicaciones.
- Mantén la brevedad (máximo 2 a 3 oraciones).
PROMPT;

        return $this->generarTextoLibre($prompt, 500);
    }

    /**
     * Generar Ficha Técnica de Indicador con IA.
     */
    public function sugerirIndicador(string $accionTitulo, string $contexto = 'IPS Paraguay'): array
    {
        $prompt = <<<PROMPT
Eres un especialista en diseño de indicadores de gestión pública y de salud para el IPS de Paraguay.

Tu tarea es diseñar la Ficha Técnica de un Indicador idóneo para la siguiente Acción u Objetivo Estratégico:
- Título: "{$accionTitulo}"
- Contexto: "{$contexto}"

Genera ÚNICAMENTE un objeto JSON estricto con la siguiente estructura (sin bloque markdown ni texto extra):
{
  "nombre": "Nombre técnico y preciso del indicador",
  "codigo_letras": "IND",
  "codigo_numeros": "001",
  "dimension": "eficacia",
  "ambito": "accion_estrategica",
  "frecuencia": "trimestral",
  "cobertura": "nacional",
  "sentido": "ascendente",
  "formula": "Fórmula matemática clara (Ej: (N° de atenciones / Total programado) * 100)",
  "unidad_medida": "%",
  "fuente": "Sistema SIESS / Registro Hospitalario IPS",
  "dependencia_responsable": "Dirección de Planificación y Gestión Hospitalaria"
}

Nota de valores posibles:
- dimension: eficacia, eficiencia, calidad, economia
- ambito: objetivo_estrategico, objetivo_especifico, accion_estrategica, accion_operativa
- frecuencia: mensual, trimestral, semestral, anual
- cobertura: nacional, regional, departamental, municipal
- sentido: ascendente, descendente
PROMPT;

        try {
            $raw = $this->generarTextoLibre($prompt, 800);
            preg_match('/\{.*\}/s', $raw, $matches);
            $clean = $matches[0] ?? '{}';
            $data = json_decode($clean, true);

            if (isset($data['nombre'])) {
                return $data;
            }
        } catch (\Exception $e) {
            // fallback
        }

        return [
            'nombre'                  => 'Porcentaje de ejecución de la acción: ' . $accionTitulo,
            'codigo_letras'           => 'IND',
            'codigo_numeros'          => '001',
            'dimension'               => 'eficacia',
            'ambito'                  => 'accion_estrategica',
            'frecuencia'              => 'trimestral',
            'cobertura'               => 'nacional',
            'sentido'                 => 'ascendente',
            'formula'                 => '(N° de metas cumplidas / Total de metas programadas) * 100',
            'unidad_medida'           => '%',
            'fuente'                  => 'Sistema SIPLAN GO IPS',
            'dependencia_responsable' => 'Unidad Ejecutora IPS',
        ];
    }

    /**
     * Generar la Acción Estratégica Completa con su Resultado Intermedio e Indicador idóneo.
     */
    public function generarAccionCompleta(string $ideaBorrador): array
    {
        $prompt = <<<PROMPT
Eres un consultor senior especializado en Planificación Estratégica de Salud Pública para el Instituto de Previsión Social (IPS) de Paraguay y el Plan Nacional de Desarrollo PND 2050.

A partir del siguiente borrador o idea simple:
Idea/Borrador: "{$ideaBorrador}"

Genera ÚNICAMENTE un objeto JSON estricto sin bloques markdown ni comillas exteriores con la siguiente estructura:
{
  "accion_nombre": "Redacción técnica, formal y SMART de la Acción Estratégica de salud o gestión pública IPS",
  "resultado_intermedio": "Logro superior/resultado intermedio al que contribuye esta acción",
  "programa_presupuestario": "Programa y Actividad presupuestaria relacionada al IPS",
  "indicador": {
    "nombre": "Nombre técnico y preciso del indicador idóneo para medir esta Acción",
    "codigo_letras": "IND",
    "codigo_numeros": "001",
    "dimension": "eficacia",
    "ambito": "accion_estrategica",
    "frecuencia": "trimestral",
    "cobertura": "nacional",
    "sentido": "ascendente",
    "formula": "(N° de metas logradas / Total de metas programadas) * 100",
    "unidad_medida": "%",
    "fuente": "Sistema Informático Hospitalario (SIH / SIESS IPS)",
    "dependencia_responsable": "Dirección de Planificación y Gestión IPS"
  }
}

Valores posibles de dimensión: eficacia, eficiencia, calidad, economia
Valores posibles de sentido: ascendente, descendente
Valores posibles de frecuencia: mensual, trimestral, semestral, anual
PROMPT;

        try {
            $raw = $this->generarTextoLibre($prompt, 1000);
            preg_match('/\{.*\}/s', $raw, $matches);
            $clean = $matches[0] ?? '{}';
            $data = json_decode($clean, true);

            return is_array($data) ? $data : [];
        } catch (\Exception $e) {
            return [];
        }
    }
}
