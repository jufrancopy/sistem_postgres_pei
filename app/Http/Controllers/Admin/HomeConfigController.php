<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeConfiguration;
use App\Admin\Planificacion\Foda\FodaPerfil;
use App\Admin\Planificacion\Foda\FodaAnalisis;
use App\Admin\Planificacion\Pei\PeiProfile;
use Illuminate\Http\Request;

class HomeConfigController extends Controller
{
    public function edit()
    {
        $config = HomeConfiguration::firstOrNew([]);
        $config->save();

        $fodaProfiles = FodaPerfil::where('type', 'consolidado')->orderBy('name')->get();
        $fodaAnalisis = collect();
        if ($config->foda_profile_id) {
            $fodaAnalisis = FodaAnalisis::where('perfil_id', $config->foda_profile_id)->orderBy('created_at', 'desc')->get();
        }

        $peiProfiles = PeiProfile::whereIsRoot()->where('type', 'corporative')->orderByDesc('year_start')->get();

        return view('admin.home.config', compact('config', 'fodaProfiles', 'fodaAnalisis', 'peiProfiles'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'foda_profile_id' => 'nullable|exists:foda_perfiles,id',
            'foda_analisis_id' => 'nullable|exists:foda_analisis,id',
            'pei_profile_id' => 'nullable|exists:pei_profiles,id',
            'show_foda' => 'boolean',
            'show_pei' => 'boolean',
            'show_riiss' => 'boolean',
        ]);

        $config = HomeConfiguration::firstOrNew([]);
        $config->fill($request->only([
            'foda_profile_id',
            'foda_analisis_id',
            'pei_profile_id',
            'show_foda',
            'show_pei',
            'show_riiss',
        ]));
        $config->save();

        return redirect()->route('home')
            ->with('success', 'Configuración del dashboard actualizada correctamente.');
    }

    public function save(Request $request)
    {
        $request->validate([
            'campo' => 'required|in:foda_profile_id,foda_analisis_id,pei_profile_id',
            'valor' => 'nullable|integer',
        ]);

        $rules = [
            'foda_profile_id'  => 'nullable|exists:foda_perfiles,id',
            'foda_analisis_id' => 'nullable|exists:foda_analisis,id',
            'pei_profile_id'   => 'nullable|exists:pei_profiles,id',
        ];

        $request->validate([$request->campo => $rules[$request->campo]]);

        $config = HomeConfiguration::firstOrNew([]);
        if (!$config->exists) {
            $config->save();
        }

        $campo = $request->campo;
        $config->$campo = $request->valor ?: null;
        $config->save();

        return response()->json([
            'ok'    => true,
            'campo' => $campo,
            'valor' => $config->$campo,
        ]);
    }

    // ── Toggle individual via AJAX ────────────────────────────────────────────
    public function toggle(Request $request)
    {
        $request->validate([
            'campo' => 'required|in:show_foda,show_pei,show_riiss',
        ]);

        $config = HomeConfiguration::firstOrNew([]);
        if (!$config->exists) $config->save();

        $campo = $request->campo;
        $config->$campo = !$config->$campo;
        $config->save();

        return response()->json([
            'ok'    => true,
            'campo' => $campo,
            'valor' => $config->$campo,
        ]);
    }
}
