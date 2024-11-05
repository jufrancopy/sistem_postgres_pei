<?php

namespace App\Http\Controllers\Admin\Globales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class OpenAIController extends Controller
{
    public function generarEncuesta($tema)
    {
        $client = new Client();
        $apiKey = env('OPENAI_API_KEY'); // Asegúrate de configurar tu API key en el archivo .env

        $prompt = "Genera una pregunta de encuesta sobre el tema: $tema y proporciona cinco opciones de respuesta.";

        try {
            $response = $client->post('https://api.openai.com/v1/chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => 'gpt-3.5-turbo',
                    'messages' => [
                        ['role' => 'user', 'content' => $prompt],
                    ],
                    'max_tokens' => 150, // Aumentamos los tokens para recibir más texto si es necesario
                ],
            ]);

            $responseBody = json_decode($response->getBody(), true);
            $generatedText = $responseBody['choices'][0]['message']['content'];

            // Separar la pregunta de las opciones de respuesta (asumiendo que el modelo devuelva un formato claro)
            $partes = explode("\n", trim($generatedText));
            $pregunta = array_shift($partes); // La primera línea es la pregunta
            $opciones = array_map('trim', $partes); // Las siguientes son las opciones

            // Ajustar las opciones para que sean solo 5
            $opciones = array_slice($opciones, 0, 5);
            $respuestaCorrecta = $opciones[0]; // Puedes ajustar esto según la lógica que prefieras

            return response()->json([
                'pregunta' => $pregunta,
                'opciones' => $opciones,
                'respuesta_correcta' => $respuestaCorrecta,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
