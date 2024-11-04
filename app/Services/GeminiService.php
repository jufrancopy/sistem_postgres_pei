<?php

namespace App\Services;

use GuzzleHttp\Client;

class GeminiService
{
    protected $client;
    protected $apiKey;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = config('services.gemini.api_key'); // Asegúrate de tener tu clave en .env
    }

    public function generateQuestions($topic)
    {

        $response = $this->client->post('https://generativelanguage.googleapis.com/v1beta/corpora/your-corpus-id:query', [
            'json' => [
                'topic' => $topic,
                'api_key' => $this->apiKey,
            ],
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }
}
