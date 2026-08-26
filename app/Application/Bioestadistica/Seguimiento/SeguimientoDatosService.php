<?php

namespace App\Application\Bioestadistica\Seguimiento;

use App\Models\Bioestadistica\Establecimiento;
use App\Models\Bioestadistica\Formulario;
use App\Models\Bioestadistica\Record;
use App\Models\User;
use Illuminate\Support\Collection;

class SeguimientoDatosService
{
    public const TAB_ACTIVIDAD = 'actividad';
    public const TAB_PENDIENTES = 'pendientes';

    /**
     * @param  array{
     *   periodo_anio:int,
     *   periodo_mes:int,
     *   formulario_ids?:array<int,int>,
     *   departamento_id?:int|null,
     *   distrito_id?:int|null,
     *   establecimiento_id?:int|null,
     *   estado?:string|null
     * }  $filters
     * @return Collection<int, array<string, mixed>>
     */
    public function actividad(User $user, array $filters): Collection
    {
        $query = Record::query()
            ->forUser($user)
            ->with([
                'formulario:id,codigo,nombre',
                'establecimiento.distrito.departamento',
                'creator:id,name',
                'updater:id,name',
                'submitter:id,name',
                'approver:id,name',
            ])
            ->where('periodo_anio', $filters['periodo_anio'])
            ->where('periodo_mes', $filters['periodo_mes'])
            ->when(
                ! empty($filters['formulario_ids']),
                fn ($q) => $q->whereIn('formulario_id', $filters['formulario_ids'])
            )
            ->when(
                ! empty($filters['establecimiento_id']),
                fn ($q) => $q->where('establecimiento_id', $filters['establecimiento_id'])
            )
            ->when(
                ! empty($filters['estado']),
                fn ($q) => $q->where('estado', $filters['estado'])
            )
            ->when(
                ! empty($filters['distrito_id']),
                fn ($q) => $q->whereHas(
                    'establecimiento',
                    fn ($est) => $est->where('distrito_id', $filters['distrito_id'])
                )
            )
            ->when(
                ! empty($filters['departamento_id']),
                fn ($q) => $q->whereHas(
                    'establecimiento.distrito',
                    fn ($dist) => $dist->where('departamento_id', $filters['departamento_id'])
                )
            )
            ->orderBy('establecimiento_id')
            ->orderBy('formulario_id')
            ->limit(5000);

        return $query->get()->map(fn (Record $record) => [
            'record_id' => $record->id,
            'usuario_ultimo' => $record->updater?->name
                ?? $record->submitter?->name
                ?? $record->creator?->name
                ?? '—',
            'establecimiento' => $record->establecimiento?->nombre ?? '—',
            'distrito' => $record->establecimiento?->distrito?->nombre ?? '—',
            'departamento' => $record->establecimiento?->distrito?->departamento?->nombre ?? '—',
            'formulario' => $record->formulario?->codigo ?? '—',
            'formulario_nombre' => $record->formulario?->nombre ?? '—',
            'periodo' => sprintf('%04d-%02d', $record->periodo_anio, $record->periodo_mes),
            'estado' => $record->estado,
            'estado_label' => Record::estadoLabel($record->estado),
            'created_by' => $record->creator?->name ?? '—',
            'created_by_id' => $record->created_by,
            'updated_by' => $record->updater?->name ?? '—',
            'updated_by_id' => $record->updated_by,
            'submitted_by' => $record->submitter?->name ?? '—',
            'submitted_by_id' => $record->submitted_by,
            'approved_by' => $record->approver?->name ?? '—',
            'approved_by_id' => $record->approved_by,
            'submitted_at' => optional($record->submitted_at)?->format('d/m/Y H:i'),
            'approved_at' => optional($record->approved_at)?->format('d/m/Y H:i'),
        ]);
    }

    /**
     * @param  array{
     *   periodo_anio:int,
     *   periodo_mes:int,
     *   formulario_ids?:array<int,int>,
     *   departamento_id?:int|null,
     *   distrito_id?:int|null,
     *   establecimiento_id?:int|null
     * }  $filters
     * @return array{
     *   rows: Collection<int, array<string, mixed>>,
     *   summary: array{pendientes:int,sin_abrir:int,sin_enviar:int,al_dia:int}
     * }
     */
    public function pendientes(User $user, array $filters): array
    {
        $forms = Formulario::query()
            ->where('estado', 'activo')
            ->when(
                ! empty($filters['formulario_ids']),
                fn ($q) => $q->whereIn('id', $filters['formulario_ids'])
            )
            ->ordenSp()
            ->get(['id', 'codigo', 'nombre']);

        $establishments = Establecimiento::query()
            ->with(['distrito.departamento'])
            ->whereNotNull('distrito_id')
            ->when(
                ! Record::userHasGlobalAccess($user),
                fn ($q) => $q->whereIn('id', Record::assignedEstablishmentIds($user))
            )
            ->when(
                ! empty($filters['establecimiento_id']),
                fn ($q) => $q->where('id', $filters['establecimiento_id'])
            )
            ->when(
                ! empty($filters['distrito_id']),
                fn ($q) => $q->where('distrito_id', $filters['distrito_id'])
            )
            ->when(
                ! empty($filters['departamento_id']),
                fn ($q) => $q->whereHas(
                    'distrito',
                    fn ($dist) => $dist->where('departamento_id', $filters['departamento_id'])
                )
            )
            ->orderBy('nombre')
            ->get();

        $records = Record::query()
            ->forUser($user)
            ->with(['updater:id,name', 'creator:id,name', 'submitter:id,name'])
            ->where('periodo_anio', $filters['periodo_anio'])
            ->where('periodo_mes', $filters['periodo_mes'])
            ->when(
                $forms->isNotEmpty(),
                fn ($q) => $q->whereIn('formulario_id', $forms->pluck('id'))
            )
            ->when(
                $establishments->isNotEmpty(),
                fn ($q) => $q->whereIn('establecimiento_id', $establishments->pluck('id'))
            )
            ->get()
            ->groupBy(fn (Record $record) => $record->establecimiento_id.'|'.$record->formulario_id);

        $rows = collect();
        $summary = ['pendientes' => 0, 'sin_abrir' => 0, 'sin_enviar' => 0, 'al_dia' => 0];

        foreach ($establishments as $establecimiento) {
            foreach ($forms as $formulario) {
                $key = $establecimiento->id.'|'.$formulario->id;
                /** @var Collection<int, Record> $group */
                $group = $records->get($key, collect());

                if ($group->isEmpty()) {
                    $summary['sin_abrir']++;
                    $summary['pendientes']++;
                    $rows->push([
                        'establecimiento' => $establecimiento->nombre,
                        'distrito' => $establecimiento->distrito?->nombre ?? '—',
                        'departamento' => $establecimiento->distrito?->departamento?->nombre ?? '—',
                        'formulario' => $formulario->codigo,
                        'formulario_nombre' => $formulario->nombre,
                        'situacion' => 'sin_abrir',
                        'situacion_label' => 'Sin abrir',
                        'estado' => null,
                        'estado_label' => '—',
                        'ultimo_actor' => '—',
                        'ultimo_actor_id' => null,
                    ]);
                    continue;
                }

                $enviados = $group->filter(
                    fn (Record $record) => in_array($record->estado, [
                        Record::ESTADO_ENVIADO,
                        Record::ESTADO_APROBADO,
                    ], true)
                );

                if ($enviados->isNotEmpty()) {
                    $summary['al_dia']++;
                    continue;
                }

                $latest = $group->sortByDesc('updated_at')->first();
                $summary['sin_enviar']++;
                $summary['pendientes']++;
                $actor = $latest->updater ?? $latest->submitter ?? $latest->creator;
                $rows->push([
                    'establecimiento' => $establecimiento->nombre,
                    'distrito' => $establecimiento->distrito?->nombre ?? '—',
                    'departamento' => $establecimiento->distrito?->departamento?->nombre ?? '—',
                    'formulario' => $formulario->codigo,
                    'formulario_nombre' => $formulario->nombre,
                    'situacion' => 'sin_enviar',
                    'situacion_label' => 'Sin enviar',
                    'estado' => $latest->estado,
                    'estado_label' => Record::estadoLabel($latest->estado),
                    'ultimo_actor' => $actor?->name ?? '—',
                    'ultimo_actor_id' => $actor?->id,
                ]);
            }
        }

        return [
            'rows' => $rows->values(),
            'summary' => $summary,
        ];
    }
}
