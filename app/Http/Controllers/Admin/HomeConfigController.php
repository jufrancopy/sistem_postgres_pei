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
            'foda_profile_id'  => 'nullable',
            'foda_analisis_id' => 'nullable',
            'pei_profile_id'   => 'nullable',
            'show_foda'        => 'boolean',
            'show_pei'         => 'boolean',
            'show_riiss'       => 'boolean',
        ]);

        if ($request->filled('foda_profile_id') && !FodaPerfil::where('id', $request->foda_profile_id)->exists()) {
            return back()->withErrors(['foda_profile_id' => 'Perfil FODA no válido.']);
        }
        if ($request->filled('foda_analisis_id') && !FodaAnalisis::where('id', $request->foda_analisis_id)->exists()) {
            return back()->withErrors(['foda_analisis_id' => 'Análisis FODA no válido.']);
        }
        if ($request->filled('pei_profile_id') && !PeiProfile::where('id', $request->pei_profile_id)->exists()) {
            return back()->withErrors(['pei_profile_id' => 'Perfil PEI no válido.']);
        }

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
            'valor' => 'nullable',
        ]);

        $campo = $request->campo;
        $valor = $request->valor ?: null;

        if ($valor !== null) {
            $exists = false;
            if ($campo === 'foda_profile_id') {
                $exists = FodaPerfil::where('id', $valor)->exists();
            } elseif ($campo === 'foda_analisis_id') {
                $exists = FodaAnalisis::where('id', $valor)->exists();
            } elseif ($campo === 'pei_profile_id') {
                $exists = PeiProfile::where('id', $valor)->exists();
            }

            if (!$exists) {
                return response()->json([
                    'message' => 'El valor seleccionado no es válido.',
                    'errors'  => [$campo => ['El registro seleccionado no existe.']]
                ], 422);
            }
        }

        $config = HomeConfiguration::firstOrNew([]);
        if (!$config->exists) {
            $config->save();
        }

        $config->$campo = $valor;
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

    // ── Variables Globales del Sistema ─────────────────────────────────────────
    public function editGlobalSettings()
    {
        $config = HomeConfiguration::firstOrNew([]);
        if (!$config->exists) {
            $config->save();
        }

        return view('admin.globales.configuracion_global', compact('config'));
    }

    public function updateGlobalSettings(Request $request)
    {
        $request->validate([
            'site_name'     => 'required|string|max:255',
            'logo_file'     => 'nullable|image|mimes:jpeg,png,jpg,svg,gif,webp|max:5120',
            'logo_url'      => 'nullable|string|max:1000',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:100',
            'opening_hours' => 'nullable|string|max:255',
            'address'       => 'nullable|string|max:500',
            'footer_text'   => 'nullable|string|max:1000',
        ]);

        $config = HomeConfiguration::firstOrNew([]);
        $config->fill($request->only([
            'site_name',
            'contact_email',
            'contact_phone',
            'opening_hours',
            'address',
            'footer_text',
        ]));

        if ($request->hasFile('logo_file')) {
            $path = $request->file('logo_file')->store('logos', 'public');
            $config->logo_url = asset('storage/' . $path);
        } elseif ($request->has('logo_url')) {
            $config->logo_url = $request->logo_url;
        }

        $config->save();

        return redirect()->back()->with('success', '¡Variables globales del sistema y logo institucional actualizados exitosamente!');
    }
}
