<?php

namespace App\Http\Controllers\Admin\Planificacion;

use App\Http\Controllers\Controller;
use App\Admin\Planificacion\Pei\PeiProfile;
use Illuminate\Http\Request;

class PublicPeiController extends Controller
{
    // ── Vista pública por token (sin auth) ───────────────────────────────────
    public function show(string $token)
    {
        $profile = PeiProfile::where('public_token', $token)
            ->where('level', 'master')
            ->with([
                'children.children.children.indicador',
                'children.children.children.responsibles',
                'children.children.children.activityTasks',
                'children.marcos',
            ])
            ->firstOrFail();

        $nivelesDefault = ['master'=>'PEI','axi'=>'Eje Estratégico','goal'=>'Objetivo','action'=>'Acción'];
        $niveles = $nivelesDefault;
        if ($profile->nivel_label) {
            $decoded = json_decode($profile->nivel_label, true);
            if (is_array($decoded)) $niveles = array_merge($nivelesDefault, $decoded);
        }

        // BSC — agrupar ejes por perspectiva
        $bscLabels  = PeiProfile::BSC_PERSPECTIVAS;
        $bscColores = [
            'financiera'  => ['color'=>'#28a745','icon'=>'fa-dollar-sign'],
            'clientes'    => ['color'=>'#1976d2','icon'=>'fa-users'],
            'procesos'    => ['color'=>'#f57c00','icon'=>'fa-cogs'],
            'aprendizaje' => ['color'=>'#7c3aed','icon'=>'fa-graduation-cap'],
            'sin_bsc'     => ['color'=>'#6c757d','icon'=>'fa-question'],
        ];
        $perspectivas = collect($bscColores)->mapWithKeys(fn($v, $k) => [
            $k => array_merge($v, ['label' => $bscLabels[$k] ?? 'Sin perspectiva', 'ejes' => collect()])
        ]);

        $bscLevel = $niveles['bsc_level'] ?? 'axi';

        if ($bscLevel === 'goal') {
            // BSC en Nivel 2 (Objetivo Específico / Meta)
            foreach ($profile->children->sortBy('order_item') as $axi) {
                foreach ($axi->children->sortBy('order_item') as $goal) {
                    $key = $goal->bsc_perspectiva ?? 'sin_bsc';
                    if (!$perspectivas->has($key)) $key = 'sin_bsc';

                    $acciones = $goal->children;
                    $total    = $acciones->count();
                    $verde    = $acciones->where('semaforo','verde')->count();
                    $amarillo = $acciones->where('semaforo','amarillo')->count();
                    $rojo     = $acciones->where('semaforo','rojo')->count();

                    $sem = 'sin-datos';
                    if ($total > 0) {
                        $pct = ($verde + $amarillo * 0.5) / $total * 100;
                        $sem = $pct >= 75 ? 'verde' : ($pct >= 50 ? 'amarillo' : 'rojo');
                    }

                    $perspectivas[$key]['ejes']->push([
                        'id'       => $goal->id,
                        'name'     => strip_tags($goal->name),
                        'axi_name' => strip_tags($axi->name),
                        'semaforo' => $sem,
                        'verde'    => $verde,
                        'amarillo' => $amarillo,
                        'rojo'     => $rojo,
                        'total'    => $total,
                        'ri'       => null,
                        'objetivos'=> [
                            [
                                'name'    => strip_tags($goal->name),
                                'acciones'=> $acciones->map(fn($a) => [
                                    'name'     => strip_tags($a->name),
                                    'semaforo' => $a->semaforo ?? 'sin-datos',
                                    'indicador'=> $a->indicador ? $a->indicador->nombre : null,
                                ])->values(),
                            ]
                        ],
                    ]);
                }
            }
        } else {
            // Nivel 1 clásico (axi)
            foreach ($profile->children->sortBy('order_item') as $axi) {
                $key = $axi->bsc_perspectiva ?? 'sin_bsc';
                if (!$perspectivas->has($key)) $key = 'sin_bsc';

                $acciones = $axi->descendants()->where('level','action')->get();
                $total    = $acciones->count();
                $verde    = $acciones->where('semaforo','verde')->count();
                $amarillo = $acciones->where('semaforo','amarillo')->count();
                $rojo     = $acciones->where('semaforo','rojo')->count();

                $sem = 'sin-datos';
                if ($total > 0) {
                    $pct = ($verde + $amarillo * 0.5) / $total * 100;
                    $sem = $pct >= 75 ? 'verde' : ($pct >= 50 ? 'amarillo' : 'rojo');
                }

                $perspectivas[$key]['ejes']->push([
                    'id'       => $axi->id,
                    'name'     => strip_tags($axi->name),
                    'axi_name' => null,
                    'semaforo' => $sem,
                    'verde'    => $verde,
                    'amarillo' => $amarillo,
                    'rojo'     => $rojo,
                    'total'    => $total,
                    'ri'       => $axi->resultado_intermedio,
                    'objetivos'=> $axi->children->map(fn($g) => [
                        'name'    => strip_tags($g->name),
                        'acciones'=> $g->children->map(fn($a) => [
                            'name'     => strip_tags($a->name),
                            'semaforo' => $a->semaforo ?? 'sin-datos',
                            'indicador'=> $a->indicador ? $a->indicador->nombre : null,
                        ])->values(),
                    ])->values(),
                ]);
            }
        }

        $perspectivas = $perspectivas->filter(fn($p) => $p['ejes']->count() > 0);

        $tabsHabilitadas = $profile->public_tabs ?? ['bsc', 'matriz', 'mecip', 'planilla'];

        return view('public.pei.show', compact('profile', 'niveles', 'perspectivas', 'token', 'tabsHabilitadas'));
    }

    // ── Generar / regenerar token (con auth) ─────────────────────────────────
    public function generateToken(Request $request, string $profileId)
    {
        $profile = PeiProfile::findOrFail($profileId);

        $tabs = $request->input('tabs', ['bsc', 'matriz', 'mecip', 'planilla']);
        $profile->update(['public_tabs' => $tabs]);

        $token = $profile->generatePublicToken();

        return response()->json([
            'ok'    => true,
            'token' => $token,
            'url'   => url('/public/pei/' . $token),
        ]);
    }

    // ── Revocar token ─────────────────────────────────────────────────────────
    public function revokeToken(string $profileId)
    {
        $profile = PeiProfile::findOrFail($profileId);
        $profile->revokePublicToken();

        return response()->json(['ok' => true, 'message' => 'Acceso público revocado.']);
    }
}
