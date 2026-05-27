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
}
