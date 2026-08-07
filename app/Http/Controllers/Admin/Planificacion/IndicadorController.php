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

        // Letras: primeras letras de cada palabra del nombre (sin HTML), máx 4 chars
        $nombre  = strip_tags($profile->name);
        $palabras = preg_split('/\s+/', $nombre);
        $letras  = strtoupper(
            implode('', array_map(
                fn($p) => mb_substr(preg_replace('/[^a-zA-ZáéíóúÁÉÍÓÚ]/u', '', $p), 0, 1),
                array_filter($palabras, fn($p) => mb_strlen($p) > 2) // ignorar artículos cortos
            ))
        );
        $letras = mb_substr($letras, 0, 4) ?: 'IND';

        // Número: siguiente correlativo del perfil
        $ultimo = Indicador::where('pei_profile_id', $profileId)
            ->whereRaw("codigo_letras = ?", [$letras])
            ->selectRaw("MAX(CAST(NULLIF(regexp_replace(codigo_numeros, '[^0-9]', '', 'g'), '') AS INTEGER)) as max_num")
            ->value('max_num');

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
            $query->where('ambito', $ambito);
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
        ]);

        $indicador = Indicador::create(array_merge($data, [
            'pei_profile_id' => $profileId,
            'metas'          => $request->input('metas', []),
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
        ]);

        $indicador->update(array_merge($data, [
            'metas' => $request->input('metas', []),
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
