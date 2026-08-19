<?php

namespace App\Http\Controllers\Admin\Ai;

use App\Http\Controllers\Controller;
use App\Services\GroqService;
use Illuminate\Http\Request;

class AiAssistantController extends Controller
{
    protected GroqService $groqService;

    public function __construct(GroqService $groqService)
    {
        $this->groqService = $groqService;
    }

    /**
     * Mejorar redacción con Metodología SMART.
     */
    public function redactarSmart(Request $request)
    {
        $request->validate([
            'texto' => 'required|string|min:3',
            'tipo'  => 'nullable|string',
        ]);

        try {
            $mejorado = $this->groqService->redactarSmart(
                $request->input('texto'),
                $request->input('tipo', 'Objetivo/Acción Estratégica')
            );

            return response()->json([
                'success'  => true,
                'resultado' => $mejorado,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de la IA: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Sugerir Ficha Técnica de Indicador con IA.
     */
    public function sugerirIndicador(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|min:3',
        ]);

        try {
            $indicador = $this->groqService->sugerirIndicador(
                $request->input('titulo'),
                $request->input('contexto', 'Instituto de Previsión Social (IPS) Paraguay')
            );

            return response()->json([
                'success' => true,
                'data'    => $indicador,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al generar indicador: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Generar la Acción Estratégica Completa con su Resultado Intermedio e Indicador idóneo.
     */
    public function generarAccionCompleta(Request $request)
    {
        $request->validate([
            'borrador' => 'required|string|min:3',
        ]);

        try {
            $data = $this->groqService->generarAccionCompleta($request->input('borrador'));

            return response()->json([
                'success' => true,
                'data'    => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de la IA: ' . $e->getMessage(),
            ], 500);
        }
    }
}
