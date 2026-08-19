<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiService
{
    protected ?string $groqKey;
    protected string $groqModel;
    protected ?string $openaiKey;

    public function __construct()
    {
        $this->groqKey   = env('GROQ_API_KEY');
        $this->groqModel = env('GROQ_MODEL', 'llama-3.3-70b-versatile');
        $this->openaiKey = env('OPENAI_API_KEY');
    }

    /**
     * Enviar prompt a la IA (Groq por defecto, OpenAI de respaldo).
     */
    public function askAi(string $systemPrompt, string $userPrompt, float $temperature = 0.3): string
    {
        // 1. Intentar con Groq (Ultra rápido)
        if ($this->groqKey) {
            try {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $this->groqKey,
                    'Content-Type'  => 'application/json',
                ])->timeout(15)->post('https://api.groq.com/openai/v1/chat/completions', [
                    'model' => $this->groqModel,
                    'messages' => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content' => $userPrompt],
                    ],
                    'temperature' => $temperature,
                ]);

                if ($response->successful()) {
                    $json = $response->json();
                    $content = $json['choices'][0]['message']['content'] ?? null;
                    if ($content) return trim($content);
                }
            } catch (\Exception $e) {
                Log::warning('Groq AI fallo: ' . $e->getMessage());
            }
        }

        // 2. Fallback: OpenAI
        if ($this->openaiKey) {
            try {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $this->openaiKey,
                    'Content-Type'  => 'application/json',
                ])->timeout(15)->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-4o-mini',
                    'messages' => [
                        ['role' => 'system', 'content' => $systemPrompt],
                        ['role' => 'user', 'content' => $userPrompt],
                    ],
                    'temperature' => $temperature,
                ]);

                if ($response->successful()) {
                    $json = $response->json();
                    $content = $json['choices'][0]['message']['content'] ?? null;
                    if ($content) return trim($content);
                }
            } catch (\Exception $e) {
                Log::error('OpenAI fallo: ' . $e->getMessage());
            }
        }

        throw new \Exception('No se pudo establecer comunicación con las APIs de Inteligencia Artificial.');
    }

    /**
     * Mejorar la redacción de un borrador según la metodología SMART y tono institucional IPS.
     */
    public function redactarSmart(string $texto, string $tipo = 'Objetivo'): string
    {
        $system = "Sos un experto consultor internacional en planificación estratégica pública y metodología SMART para el Instituto de Previsión Social (IPS) de Paraguay y el Plan Nacional de Desarrollo PND 2050. Tu tarea es recibir un borrador o texto simple de un {$tipo} y reescribirlo en un párrafo claro, técnico, formal, medible y orientado a resultados de salud/gestión pública en Paraguay. Respondé ÚNICAMENTE con el texto mejorado, sin introducciones ni saludos.";
        $user   = "Borrador a mejorar: \"{$texto}\"";

        return $this->askAi($system, $user, 0.4);
    }

    /**
     * Generar propuestas completas de Ficha de Indicador con IA.
     */
    public function sugerirIndicador(string $accionTitulo, string $contexto = ''): array
    {
        $system = "Sos un experto especialista en diseño de indicadores de gestión de salud pública para el IPS de Paraguay. Tu tarea es generar la Ficha Técnica de un Indicador idóneo para la siguiente Acción/Objetivo. Respondé ÚNICAMENTE en formato JSON válido sin bloques markdown ni comillas externas.";

        $user = "Acción u Objetivo: \"{$accionTitulo}\". Contexto adicional: \"{$contexto}\".
        Generá un objeto JSON estricto con las siguientes claves:
        {
          \"nombre\": \"Nombre técnico del indicador\",
          \"codigo_letras\": \"AAA\",
          \"codigo_numeros\": \"001\",
          \"dimension\": \"eficacia|eficiencia|calidad|economia\",
          \"ambito\": \"objetivo_estrategico|objetivo_especifico|accion_estrategica|accion_operativa\",
          \"frecuencia\": \"mensual|trimestral|semestral|anual\",
          \"cobertura\": \"nacional|regional|departamental|municipal\",
          \"sentido\": \"ascendente|descendente\",
          \"formula\": \"Fórmula matemática clara (Ej: (N° de atenciones / Total programado) * 100)\",
          \"unidad_medida\": \"Porcentaje (%) | Cantidad | Días | Minutos\",
          \"fuente\": \"Sistema de Información SIESS / Sistema Registral Hospitalario IPS\",
          \"dependencia_responsable\": \"Dirección / Jefatura correspondiente\"
        }";

        $raw = $this->askAi($system, $user, 0.2);
        
        // Limpiar posible bloque ```json ... ```
        $clean = preg_replace('/^```json\s*|\s*```$/i', '', trim($raw));
        $data = json_decode($clean, true);

        if (!$data || !isset($data['nombre'])) {
            throw new \Exception('No se pudo estructurar la propuesta de indicador con IA.');
        }

        return $data;
    }
}
