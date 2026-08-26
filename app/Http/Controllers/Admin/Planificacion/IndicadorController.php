<?php

namespace App\Http\Controllers\Admin\Planificacion;

use App\Http\Controllers\Controller;
use App\Models\Planificacion\Indicador;
use App\Admin\Planificacion\Pei\PeiProfile;
use Illuminate\Http\Request;

class IndicadorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // GET /pei-profiles/{profileId}/indicadores/siguiente-codigo
    public function siguienteCodigo(string $profileId)
    {
        $profile = PeiProfile::findOrFail($profileId);

        // Prefijo predeterminado limpio
        $nombre  = strip_tags($profile->name);
        $palabras = preg_split('/\s+/', $nombre);
        $letras  = strtoupper(
            implode('', array_map(
                fn($p) => mb_substr(preg_replace('/[^a-zA-ZáéíóúÁÉÍÓÚ]/u', '', $p), 0, 1),
                array_filter($palabras, fn($p) => mb_strlen($p) > 2)
            ))
        );
        $letras = mb_substr($letras, 0, 4) ?: 'IND';

        // Obtener todos los IDs de la estructura PEI (raíz + descendientes)
        $allProfileIds = PeiProfile::whereIn('id', $profile->descendantsAndSelf($profileId)->pluck('id'))->pluck('id')->toArray();
        if (empty($allProfileIds)) {
            $allProfileIds = [$profileId];
        }

        // Buscar el máximo correlativo numérico en todo el PEI
        $ultimo = Indicador::whereIn('pei_profile_id', $allProfileIds)
            ->selectRaw("MAX(CAST(NULLIF(regexp_replace(codigo_numeros, '[^0-9]', '', 'g'), '') AS INTEGER)) as max_num")
            ->value('max_num');

        // Si no hay en el PEI actual, buscar globalmente para evitar duplicados si aplica
        if (!$ultimo) {
            $ultimo = Indicador::selectRaw("MAX(CAST(NULLIF(regexp_replace(codigo_numeros, '[^0-9]', '', 'g'), '') AS INTEGER)) as max_num")
                ->value('max_num');
        }

        $siguiente = str_pad(($ultimo ?? 0) + 1, 3, '0', STR_PAD_LEFT);

        return response()->json([
            'letras'   => $letras,
            'numeros'  => $siguiente,
            'completo' => $letras . '-' . $siguiente,
        ]);
    }

    // GET /pei-profiles/{profileId}/indicadores/modulo — vista dedicada
    public function modulo(string $profileId)
    {
        $profile     = PeiProfile::findOrFail($profileId);
        $indicadores = Indicador::where('pei_profile_id', $profileId)
            ->orderBy('codigo_letras')->orderBy('codigo_numeros')
            ->get();

        return view('admin.planificacion.indicadores.modulo', compact('profile', 'indicadores'));
    }

    // GET /pei-profiles/{profileId}/indicadores/{id}/detalle — HTML para modal
    public function detalle(string $profileId, int $id)
    {
        $profile   = PeiProfile::findOrFail($profileId);
        $indicador = Indicador::where('pei_profile_id', $profileId)->findOrFail($id);
        return view('admin.planificacion.indicadores._modal_detalle', compact('profile', 'indicador'));
    }

    // GET /pei-profiles/{profileId}/indicadores/buscar?q=texto&ambito=xxx
    public function buscar(Request $request, string $profileId)
    {
        $q      = $request->get('q', '');
        $ambito = $request->get('ambito');

        $query = Indicador::where('pei_profile_id', $profileId)
            ->where(function($query) use ($q) {
                $query->whereRaw('LOWER(nombre) LIKE ?', ['%' . strtolower($q) . '%'])
                      ->orWhereRaw('LOWER(codigo_letras) LIKE ?', ['%' . strtolower($q) . '%'])
                      ->orWhereRaw('LOWER(codigo_numeros) LIKE ?', ['%' . strtolower($q) . '%']);
            });

        if (!empty($ambito)) {
            $query->where(function($qAm) use ($ambito, $q) {
                $qAm->where('ambito', $ambito)
                    ->orWhere('ambito', str_replace('_', ' ', $ambito))
                    ->orWhereRaw('LOWER(ambito) = ?', [strtolower($ambito)]);
                if (!empty($q)) {
                    $qAm->orWhereNull('ambito');
                }
            });
        }

        $indicadores = $query->orderBy('codigo_letras')->orderBy('codigo_numeros')
            ->limit(20)
            ->get();

        return response()->json($indicadores->map(fn($i) => [
            'id'        => $i->id,
            'text'      => '[' . $i->codigoCompleto() . '] ' . $i->nombre,
            'codigo'    => $i->codigoCompleto(),
            'dimension' => $i->dimension,
            'sentido'   => $i->sentido,
            'ambito'    => $i->ambito,
        ]));
    }

    // GET /pei-profiles/{profileId}/indicadores
    public function porPerfil(string $profileId)
    {
        $profile = PeiProfile::findOrFail($profileId);
        $indicadores = Indicador::where('pei_profile_id', $profileId)
            ->orderBy('created_at')
            ->get();

        return response()->json($indicadores->map(fn($i) => [
            'id'                     => $i->id,
            'codigo'                 => $i->codigoCompleto(),
            'codigo_letras'          => $i->codigo_letras,
            'codigo_numeros'         => $i->codigo_numeros,
            'nombre'                 => $i->nombre,
            'dimension'              => $i->dimension,
            'ambito'                 => $i->ambito,
            'descripcion'            => $i->descripcion,
            'variables'              => $i->variables,
            'formula'                => $i->formula,
            'unidad_medida'          => $i->unidad_medida,
            'frecuencia'             => $i->frecuencia,
            'frecuencia_otro'        => $i->frecuencia_otro,
            'cobertura'              => $i->cobertura,
            'sentido'                => $i->sentido,
            'linea_base_anio'        => $i->linea_base_anio,
            'linea_base_valor'       => $i->linea_base_valor,
            'metas'                  => $i->metas ?? [],
            'fuente'                 => $i->fuente,
            'dependencia_responsable'=> $i->dependencia_responsable,
            'comentarios'            => $i->comentarios,
        ]));
    }

    // GET /pei-profiles/{profileId}/indicadores/{id}
    public function show(string $profileId, int $id)
    {
        $indicador = Indicador::where('pei_profile_id', $profileId)->findOrFail($id);

        return response()->json([
            'id'                     => $indicador->id,
            'codigo'                 => $indicador->codigoCompleto(),
            'codigo_letras'          => $indicador->codigo_letras,
            'codigo_numeros'         => $indicador->codigo_numeros,
            'nombre'                 => $indicador->nombre,
            'dimension'              => $indicador->dimension,
            'ambito'                 => $indicador->ambito,
            'descripcion'            => $indicador->descripcion,
            'variables'              => $indicador->variables,
            'formula'                => $indicador->formula,
            'unidad_medida'          => $indicador->unidad_medida,
            'frecuencia'             => $indicador->frecuencia,
            'frecuencia_otro'        => $indicador->frecuencia_otro,
            'cobertura'              => $indicador->cobertura,
            'sentido'                => $indicador->sentido,
            'linea_base_anio'        => $indicador->linea_base_anio,
            'linea_base_valor'       => $indicador->linea_base_valor,
            'metas'                  => $indicador->metas ?? [],
            'fuente'                 => $indicador->fuente,
            'dependencia_responsable'=> $indicador->dependencia_responsable,
            'comentarios'            => $indicador->comentarios,
        ]);
    }

    // POST /pei-profiles/{profileId}/indicadores
    public function store(Request $request, string $profileId)
    {
        PeiProfile::findOrFail($profileId);

        $data = $request->validate([
            'nombre'                  => 'required|string|max:500',
            'codigo_letras'           => 'nullable|string|max:10',
            'codigo_numeros'          => 'nullable|string|max:10',
            'dimension'               => 'required|in:eficiencia,eficacia,calidad,economia',
            'ambito'                  => 'required|in:objetivo_estrategico,objetivo_especifico,accion_estrategica,accion_operativa',
            'descripcion'             => 'nullable|string',
            'variables'               => 'nullable|string',
            'formula'                 => 'nullable|string|max:500',
            'unidad_medida'           => 'nullable|string|max:100',
            'frecuencia'              => 'required|in:mensual,trimestral,semestral,anual,otro',
            'frecuencia_otro'         => 'nullable|string|max:100',
            'cobertura'               => 'required|in:nacional,regional,departamental,municipal',
            'sentido'                 => 'required|in:ascendente,descendente',
            'linea_base_anio'         => 'nullable|integer|min:2000|max:2100',
            'linea_base_valor'        => 'nullable|string|max:200',
            'metas'                   => 'nullable|array',
            'metas.*.anio'            => 'required_with:metas|integer',
            'metas.*.valor'           => 'required_with:metas|string',
            'fuente'                  => 'nullable|string|max:500',
            'dependencia_responsable' => 'nullable|string|max:300',
            'comentarios'             => 'nullable|string',
            'creado_con_ia'           => 'nullable|boolean',
        ]);

        if (empty($data['codigo_letras']) || empty($data['codigo_numeros'])) {
            $codeRes = $this->siguienteCodigo($profileId);
            $codeData = json_decode($codeRes->getContent(), true);
            if (empty($data['codigo_letras']))  $data['codigo_letras']  = $codeData['letras'] ?? 'IND';
            if (empty($data['codigo_numeros'])) $data['codigo_numeros'] = $codeData['numeros'] ?? '001';
        }

        $indicador = Indicador::create(array_merge($data, [
            'pei_profile_id' => $profileId,
            'metas'          => $request->input('metas', []),
            'creado_con_ia'  => $request->boolean('creado_con_ia', false),
        ]));

        return response()->json([
            'ok'        => true,
            'indicador' => array_merge($indicador->toArray(), ['codigo' => $indicador->codigoCompleto()]),
        ], 201);
    }

    // PUT /pei-profiles/{profileId}/indicadores/{id}
    public function update(Request $request, string $profileId, int $id)
    {
        $indicador = Indicador::where('pei_profile_id', $profileId)->findOrFail($id);

        $data = $request->validate([
            'nombre'                  => 'required|string|max:500',
            'codigo_letras'           => 'nullable|string|max:10',
            'codigo_numeros'          => 'nullable|string|max:10',
            'dimension'               => 'required|in:eficiencia,eficacia,calidad,economia',
            'ambito'                  => 'required|in:objetivo_estrategico,objetivo_especifico,accion_estrategica,accion_operativa',
            'descripcion'             => 'nullable|string',
            'variables'               => 'nullable|string',
            'formula'                 => 'nullable|string|max:500',
            'unidad_medida'           => 'nullable|string|max:100',
            'frecuencia'              => 'required|in:mensual,trimestral,semestral,anual,otro',
            'frecuencia_otro'         => 'nullable|string|max:100',
            'cobertura'               => 'required|in:nacional,regional,departamental,municipal',
            'sentido'                 => 'required|in:ascendente,descendente',
            'linea_base_anio'         => 'nullable|integer|min:2000|max:2100',
            'linea_base_valor'        => 'nullable|string|max:200',
            'metas'                   => 'nullable|array',
            'metas.*.anio'            => 'required_with:metas|integer',
            'metas.*.valor'           => 'required_with:metas|string',
            'fuente'                  => 'nullable|string|max:500',
            'dependencia_responsable' => 'nullable|string|max:300',
            'comentarios'             => 'nullable|string',
            'creado_con_ia'           => 'nullable|boolean',
        ]);

        $indicador->update(array_merge($data, [
            'metas'         => $request->input('metas', []),
            'creado_con_ia' => $request->has('creado_con_ia') ? $request->boolean('creado_con_ia') : $indicador->creado_con_ia,
        ]));

        return response()->json([
            'ok'        => true,
            'indicador' => array_merge($indicador->fresh()->toArray(), ['codigo' => $indicador->codigoCompleto()]),
        ]);
    }

    // DELETE /pei-profiles/{profileId}/indicadores/{id}
    public function destroy(string $profileId, int $id)
    {
        if (!auth()->user()->hasRole('Administrador')) {
            return response()->json(['error' => 'Solo el Administrador tiene permisos para eliminar indicadores.'], 403);
        }

        $indicador = Indicador::where('pei_profile_id', $profileId)->findOrFail($id);
        $indicador->delete();
        return response()->json(['ok' => true]);
    }
}
