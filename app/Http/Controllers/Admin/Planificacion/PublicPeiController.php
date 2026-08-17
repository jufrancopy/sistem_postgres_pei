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

        $nivelesDefault = ['master'=>'PEI','axi'=>'Eje Estratégico','goal'=>'Objetivo','action'=>'Acción','bsc_level'=>'axi'];
        $niveles = $nivelesDefault;
        if ($profile->nivel_label) {
            $decoded = json_decode($profile->nivel_label, true);
            // El decoded sobreescribe los defaults; bsc_level del JSON tiene precedencia
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

        // ── Función auxiliar para agregar un eje/objetivo a una perspectiva ──
        $pushToPersp = function(string $key, array $data) use (&$perspectivas) {
            if (!$perspectivas->has($key)) $key = 'sin_bsc';
            $perspectivas[$key]['ejes']->push($data);
        };

        if ($bscLevel === 'none') {
            // BSC desactivado: no se agrupa, se deja vacío
        } elseif ($bscLevel === 'goal') {
            // BSC en Nivel 2 (Objetivo Específico / Meta)
            foreach ($profile->children->sortBy('order_item') as $axi) {
                foreach ($axi->children->sortBy('order_item') as $goal) {
                    $key = $goal->bsc_perspectiva ?? 'sin_bsc';

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

                    $pushToPersp($key, [
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
        } elseif ($bscLevel === 'both') {
            // BSC en ambos niveles: agrupa en Nivel 1 (axi) tomando perspectiva del axi,
            // y si el axi no tiene perspectiva, intenta con los hijos (goal)
            foreach ($profile->children->sortBy('order_item') as $axi) {
                $key = $axi->bsc_perspectiva ?? 'sin_bsc';

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

                $pushToPersp($key, [
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
        } else {
            // Nivel 1 clásico (axi) — Lee bsc_perspectiva directamente del nodo axi
            foreach ($profile->children->sortBy('order_item') as $axi) {
                $key = $axi->bsc_perspectiva ?? 'sin_bsc';

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

                $pushToPersp($key, [
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

    // ── Vista pública de Asesor Externo por Token ────────────────────────────
    public function showAsesor(string $token)
    {
        $profile = PeiProfile::where('asesor_token', $token)
            ->where('level', 'master')
            ->firstOrFail();

        $descendants = $profile->descendants()->get();
        $treeNodes   = $descendants->toTree();

        $nodeIds = $descendants->pluck('id')->push($profile->id)->map(fn($v) => (string)$v)->toArray();
        $stringIds = array_values(array_filter($nodeIds, fn($id) => !is_numeric($id)));
        $numericIds = array_values(array_filter($nodeIds, 'is_numeric'));

        $query = \App\Models\PlanMaestro\PlanAccion::query();
        if (!empty($stringIds)) {
            $query->whereIn('pei_profile_id', $stringIds);
        }
        if (!empty($numericIds)) {
            $query->orWhereIn('plan_id', $numericIds)->orWhereIn('eje_id', $numericIds);
        }
        $iniciativas = $query->orderBy('orden')->get();

        $isPublicAccess = true;

        return view('admin.planificacion.peis.peis.vista_asesor', compact('profile', 'treeNodes', 'descendants', 'iniciativas', 'isPublicAccess'));
    }
}
