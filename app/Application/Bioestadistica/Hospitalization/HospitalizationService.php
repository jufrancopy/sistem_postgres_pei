<?php

namespace App\Application\Bioestadistica\Hospitalization;

use App\Application\Bioestadistica\Audit\AuditService;
use App\Application\Bioestadistica\Indicators\IndicatorCacheService;
use App\Application\Bioestadistica\RecordCaptureService;
use App\Application\Bioestadistica\Sp11Matrix;
use App\Models\Bioestadistica\Establecimiento;
use App\Models\Bioestadistica\Formulario;
use App\Models\Bioestadistica\HospEpisodio;
use App\Models\Bioestadistica\Record;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class HospitalizationService
{
    public function __construct(private RecordCaptureService $capture)
    {
    }

    public function save(array $input, User $user, ?HospEpisodio $episodio = null, bool $consolidate = true): HospEpisodio
    {
        $data = $this->normalize($input);
        $this->assertEstablishment($data['establecimiento_id'], $user);
        $period = isset($input['periodo_anio'], $input['periodo_mes'])
            ? [
                'periodo_anio' => (int) $input['periodo_anio'],
                'periodo_mes' => (int) $input['periodo_mes'],
            ]
            : HospEpisodio::periodFromDates($data['fecha_ingreso'], $data['fecha_egreso']);
        $previousPeriod = $episodio ? [
            'establecimiento_id' => (int) $episodio->establecimiento_id,
            'periodo_anio' => (int) $episodio->periodo_anio,
            'periodo_mes' => (int) $episodio->periodo_mes,
        ] : null;

        $payload = array_merge($data, $period, [
            'cedula_hash' => HospEpisodio::hashCedula($data['cedula'] ?? null),
            'source_fingerprint' => $input['source_fingerprint'] ?? HospEpisodio::fingerprint($data + ['establecimiento_id' => $data['establecimiento_id']]),
            'source_import_job_id' => $input['source_import_job_id'] ?? $episodio?->source_import_job_id,
            'source_row' => $input['source_row'] ?? $episodio?->source_row,
        ]);
        if ($episodio === null) {
            $payload['created_by'] = $user->id;
        }

        $saved = DB::transaction(function () use ($episodio, $payload, $previousPeriod, $consolidate) {
            $duplicate = HospEpisodio::query()
                ->where('source_fingerprint', $payload['source_fingerprint'])
                ->when($episodio, fn ($query) => $query->where('id', '<>', $episodio->id))
                ->first();
            if ($duplicate) {
                throw ValidationException::withMessages([
                    'cedula' => 'Ya existe un episodio equivalente para ese paciente, fechas y servicio.',
                ]);
            }

            $model = $episodio ?? new HospEpisodio();
            $model->fill($payload);
            $model->save();

            if ($consolidate) {
                $this->consolidateQuietly((int) $model->establecimiento_id, (int) $model->periodo_anio, (int) $model->periodo_mes);
                if ($previousPeriod && (
                    $previousPeriod['establecimiento_id'] !== (int) $model->establecimiento_id
                    || $previousPeriod['periodo_anio'] !== (int) $model->periodo_anio
                    || $previousPeriod['periodo_mes'] !== (int) $model->periodo_mes
                )) {
                    $this->consolidateQuietly(
                        $previousPeriod['establecimiento_id'],
                        $previousPeriod['periodo_anio'],
                        $previousPeriod['periodo_mes']
                    );
                }
            }

            return $model;
        });

        return $saved->fresh(['establecimiento']);
    }

    /**
     * Guarda hasta 200 episodios y consolida una sola vez cada período afectado.
     *
     * @param  array<int, array<string, mixed>>  $rows
     * @return array{creados:int,actualizados:int,eliminados:int,omitidos:int}
     */
    public function saveBatch(
        int $establecimientoId,
        int $year,
        int $month,
        array $rows,
        User $user,
        ?Record $record = null,
        bool $strict = true
    ): array {
        $this->assertEstablishment($establecimientoId, $user);
        $periods = [];
        $summary = ['creados' => 0, 'actualizados' => 0, 'eliminados' => 0, 'omitidos' => 0];

        app(AuditService::class)->withoutAuditing(function () use (
            $establecimientoId,
            $year,
            $month,
            $rows,
            $user,
            $record,
            $strict,
            &$periods,
            &$summary
        ) {
            DB::transaction(function () use (
                $establecimientoId,
                $year,
                $month,
                $rows,
                $user,
                $record,
                $strict,
                &$periods,
                &$summary
            ) {
                foreach ($rows as $index => $row) {
                $episodio = null;
                if (! empty($row['id'])) {
                    $episodio = HospEpisodio::query()->lockForUpdate()->findOrFail((int) $row['id']);
                    if (! $episodio->isAccessibleBy($user)) {
                        abort(403);
                    }
                    $periods[$this->periodKey(
                        (int) $episodio->establecimiento_id,
                        (int) $episodio->periodo_anio,
                        (int) $episodio->periodo_mes
                    )] = [
                        (int) $episodio->establecimiento_id,
                        (int) $episodio->periodo_anio,
                        (int) $episodio->periodo_mes,
                    ];
                }

                if (! empty($row['eliminar'])) {
                    if ($episodio) {
                        $episodio->delete();
                        $summary['eliminados']++;
                    }
                    continue;
                }

                if ($episodio && empty($row['cedula'])) {
                    $row['cedula'] = $episodio->cedula;
                }

                try {
                    $saved = $this->save([
                        ...$row,
                        'establecimiento_id' => $establecimientoId,
                        'periodo_anio' => $year,
                        'periodo_mes' => $month,
                    ], $user, $episodio, false);
                } catch (ValidationException $exception) {
                    if (! $strict) {
                        $summary['omitidos']++;
                        continue;
                    }
                    $errors = [];
                    foreach ($exception->errors() as $field => $messages) {
                        $errors["rows.{$index}.{$field}"] = $messages;
                    }
                    throw ValidationException::withMessages($errors);
                }

                    if ($record) {
                        $saved->forceFill(['record_id' => $record->id])->save();
                    }

                    $summary[$episodio ? 'actualizados' : 'creados']++;
                    $periods[$this->periodKey(
                        (int) $saved->establecimiento_id,
                        (int) $saved->periodo_anio,
                        (int) $saved->periodo_mes
                    )] = [
                        (int) $saved->establecimiento_id,
                        (int) $saved->periodo_anio,
                        (int) $saved->periodo_mes,
                    ];
                }
            });
        });

        foreach ($periods as [$establishment, $periodYear, $periodMonth]) {
            $this->consolidate($establishment, $periodYear, $periodMonth, $user, $record);
        }

        return $summary;
    }

    public function reassignEpisodesToPeriod(Record $record, int $year, int $month): void
    {
        if ((int) $record->periodo_anio === $year && (int) $record->periodo_mes === $month) {
            return;
        }

        HospEpisodio::query()
            ->where('establecimiento_id', $record->establecimiento_id)
            ->where('periodo_anio', $record->periodo_anio)
            ->where('periodo_mes', $record->periodo_mes)
            ->when(
                $record->id,
                fn ($query) => $query->where(function ($inner) use ($record) {
                    $inner->where('record_id', $record->id)->orWhereNull('record_id');
                })
            )
            ->update([
                'periodo_anio' => $year,
                'periodo_mes' => $month,
            ]);
    }

    public function delete(HospEpisodio $episodio, User $user): void
    {
        $this->assertEstablishment((int) $episodio->establecimiento_id, $user);
        DB::transaction(function () use ($episodio) {
            $establecimientoId = (int) $episodio->establecimiento_id;
            $year = (int) $episodio->periodo_anio;
            $month = (int) $episodio->periodo_mes;
            $episodio->delete();
            $this->consolidateQuietly($establecimientoId, $year, $month);
        });
    }

    public function consolidate(
        int $establecimientoId,
        int $year,
        int $month,
        ?User $user = null,
        ?Record $target = null
    ): Record {
        return app(AuditService::class)->withoutAuditing(function () use ($establecimientoId, $year, $month, $user, $target) {
            return DB::transaction(function () use ($establecimientoId, $year, $month, $user, $target) {
                $formulario = $this->sp10();
                $lookup = [
                    'formulario_id' => $formulario->id,
                    'establecimiento_id' => $establecimientoId,
                    'periodo_anio' => $year,
                    'periodo_mes' => $month,
                    'estructura_departamento_id' => $target?->estructura_departamento_id,
                    'estructura_servicio_id' => $target?->estructura_servicio_id,
                ];
                $record = Record::withTrashed()->where($lookup)->lockForUpdate()->first();
                if ($record?->trashed()) {
                    $record->restore();
                }
                if (! $record) {
                    $record = Record::create($lookup + [
                        'estado' => Record::ESTADO_BORRADOR,
                        'created_by' => $user?->id,
                        'observacion' => 'Consolidado automáticamente desde episodios SP10.',
                    ]);
                }

                $metrics = $this->metrics($establecimientoId, $year, $month, (int) $record->id, $record->estructura_servicio_id);
                $values = $this->toRecordValues($formulario, $metrics);
                $this->capture->save($record->load('formulario.secciones.fields.detalle.prestaciones'), $values, true);

                HospEpisodio::query()
                    ->where('establecimiento_id', $establecimientoId)
                    ->where('periodo_anio', $year)
                    ->where('periodo_mes', $month)
                    ->where(function ($query) use ($record) {
                        $query->where('record_id', $record->id)->orWhereNull('record_id');
                    })
                    ->update(['record_id' => $record->id]);

                app(IndicatorCacheService::class)->invalidateForRecord($record);

                return $record->fresh(['values.field']);
            });
        });
    }

    /**
     * @return array<string, mixed>
     */
    public function metrics(int $establecimientoId, int $year, int $month, ?int $recordId = null, ?int $servicioId = null): array
    {
        $period = HospEpisodio::query()
            ->where('establecimiento_id', $establecimientoId)
            ->where('periodo_anio', $year)
            ->where('periodo_mes', $month)
            ->when($recordId, fn ($query) => $query->where(function ($inner) use ($recordId) {
                $inner->where('record_id', $recordId)->orWhereNull('record_id');
            }));
        $discharged = (clone $period)
            ->whereNotNull('fecha_egreso')
            ->get();
        $admitted = (clone $period)->count();

        $stayDays = $discharged->sum(fn (HospEpisodio $episodio) => $episodio->stayDays() ?? 0);
        $egresos = $discharged->count();
        $fallecidos = $discharged->where('tipo_alta', 'FALLECIDO')->count();
        $cirugias = $discharged->where('cirugia', true)->count();
        $cesareas = $discharged->where('cesarea', true)->count();
        $partos = $discharged->where('servicio', 'MATERNIDAD')->count();
        $recienNacidos = $discharged->where('recien_nacido', true)->count();
        $sp11 = $this->sp11Totals($establecimientoId, $year, $month, $servicioId);

        return [
            'ingresos_total' => $admitted,
            'egresos_total' => $egresos,
            'dias_estancia' => $stayDays,
            'estancia_media' => $egresos > 0 ? round($stayDays / $egresos, 2) : null,
            'fallecidos' => $fallecidos,
            'mortalidad' => $egresos > 0 ? round(($fallecidos / $egresos) * 100, 2) : null,
            'cirugias' => $cirugias,
            'cesareas' => $cesareas,
            'partos' => $partos,
            'porcentaje_cesareas' => $partos > 0 ? round(($cesareas / $partos) * 100, 2) : null,
            'recien_nacidos' => $recienNacidos,
            'egresos_por_servicio' => $discharged->groupBy('servicio')->map->count()->all(),
            'egresos_por_sexo' => $discharged->groupBy('sexo')->map->count()->all(),
            'pacientes_dia' => $sp11['pacientes_dia'],
            'camas_operativas' => $sp11['camas_operativas'],
            'camas_disponibles' => $sp11['camas_disponibles'],
            'ocupacion' => $sp11['camas_operativas'] > 0
                ? round(($sp11['pacientes_dia'] / $sp11['camas_operativas']) * 100, 2)
                : null,
            'rotacion' => $sp11['camas_operativas'] > 0
                ? round($egresos / $sp11['camas_operativas'], 2)
                : null,
            'intervalo_sustitucion' => $egresos > 0
                ? round(($sp11['camas_disponibles'] - $sp11['pacientes_dia']) / $egresos, 2)
                : null,
        ];
    }

    /**
     * @return array{actual: array<string, mixed>, anterior: array<string, mixed>, tendencia: array<int, array<string, mixed>>}
     */
    public function panel(int $establecimientoId, int $year, int $month): array
    {
        $current = CarbonImmutable::create($year, $month, 1);
        $previous = $current->subMonth();
        $trend = [];
        for ($i = 11; $i >= 0; $i--) {
            $point = $current->subMonths($i);
            $metrics = $this->metrics($establecimientoId, (int) $point->year, (int) $point->month);
            $trend[] = [
                'periodo' => $point->format('Y-m'),
                'egresos' => $metrics['egresos_total'],
                'estancia_media' => $metrics['estancia_media'],
                'ocupacion' => $metrics['ocupacion'],
            ];
        }

        return [
            'actual' => $this->metrics($establecimientoId, $year, $month),
            'anterior' => $this->metrics($establecimientoId, (int) $previous->year, (int) $previous->month),
            'tendencia' => $trend,
        ];
    }

    /**
     * @param  array<string, mixed>  $filter
     */
    public function countMetric(string $metric, array $context, array $filter = []): ?float
    {
        [$fromYear, $fromMonth] = $context['periodo_desde'];
        [$toYear, $toMonth] = $context['periodo_hasta'];

        $query = DB::connection('pgsql')
            ->table('bioestadistica.hosp_episodios as h')
            ->join(
                'bioestadistica.v_establecimientos_geo as g',
                'g.establecimiento_id',
                '=',
                'h.establecimiento_id'
            )
            ->whereNull('h.deleted_at')
            ->whereRaw('(h.periodo_anio * 100 + h.periodo_mes) BETWEEN ? AND ?', [
                $fromYear * 100 + $fromMonth,
                $toYear * 100 + $toMonth,
            ]);

        $merged = array_merge($context, $filter);
        foreach ([
            'establecimiento_id' => 'h.establecimiento_id',
            'departamento_id' => 'g.departamento_id',
            'distrito_id' => 'g.distrito_id',
            'microred_id' => 'g.microred_id',
            'tipo_establecimiento_id' => 'g.tipo_establecimiento_id',
            'grado_complejidad_id' => 'g.grado_complejidad_id',
            'area_gestion_id' => 'g.area_gestion_id',
        ] as $key => $column) {
            if (isset($merged[$key])) {
                $query->where($column, $merged[$key]);
            }
        }

        if ($metric !== 'ingresos') {
            $query->whereNotNull('h.fecha_egreso');
        }

        $value = match ($metric) {
            'ingresos', 'egresos' => $query->count(),
            'dias_estancia' => $query->selectRaw(
                'COALESCE(SUM(GREATEST(1, h.fecha_egreso - h.fecha_ingreso)), 0) AS result'
            )->value('result'),
            'fallecidos' => $query->where('h.tipo_alta', 'FALLECIDO')->count(),
            'cirugias' => $query->where('h.cirugia', true)->count(),
            'cesareas' => $query->where('h.cesarea', true)->count(),
            'partos' => $query->where('h.servicio', 'MATERNIDAD')->count(),
            'recien_nacidos' => $query->where('h.recien_nacido', true)->count(),
            default => throw ValidationException::withMessages([
                'expresion' => "Métrica hospitalaria «{$metric}» no permitida.",
            ]),
        };

        return $value === null ? null : (float) $value;
    }

    private function consolidateQuietly(int $establecimientoId, int $year, int $month): void
    {
        try {
            $this->consolidate($establecimientoId, $year, $month);
        } catch (\Throwable $exception) {
            report($exception);
        }
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array<string, mixed>
     */
    private function normalize(array $input): array
    {
        $ingreso = $input['fecha_ingreso'] ?? null;
        $egreso = ($input['fecha_egreso'] ?? null) ?: null;
        if (! $ingreso) {
            throw ValidationException::withMessages(['fecha_ingreso' => 'La fecha de ingreso es obligatoria.']);
        }
        if ($egreso && $egreso < $ingreso) {
            throw ValidationException::withMessages(['fecha_egreso' => 'La fecha de egreso no puede ser anterior al ingreso.']);
        }
        $sexo = HospEpisodio::normalizeSexo($input['sexo'] ?? null);
        if (($input['sexo'] ?? null) && $sexo === null) {
            throw ValidationException::withMessages(['sexo' => 'El sexo debe ser M o F.']);
        }
        $cie10 = HospEpisodio::normalizeCie10($input['cie10'] ?? null);
        if ($cie10 && ! HospEpisodio::isValidCie10($cie10)) {
            throw ValidationException::withMessages(['cie10' => 'El código CIE-10 no tiene un formato válido.']);
        }
        $edad = $input['edad'] ?? null;
        if ($edad !== null && $edad !== '' && ((int) $edad < 0 || (int) $edad > 130)) {
            throw ValidationException::withMessages(['edad' => 'La edad debe estar entre 0 y 130.']);
        }
        $tipoAlta = HospEpisodio::normalizeTipoAlta($input['tipo_alta'] ?? null);
        if ($egreso && ! $tipoAlta) {
            throw ValidationException::withMessages(['tipo_alta' => 'Indique el tipo de alta al registrar el egreso.']);
        }

        return [
            'establecimiento_id' => (int) $input['establecimiento_id'],
            'cedula' => HospEpisodio::normalizeCedula($input['cedula'] ?? null),
            'sexo' => $sexo,
            'seguro' => $this->nullableString($input['seguro'] ?? null, 80),
            'edad' => $edad === null || $edad === '' ? null : (int) $edad,
            'fecha_ingreso' => $ingreso,
            'fecha_egreso' => $egreso,
            'servicio' => HospEpisodio::normalizeServicio($input['servicio'] ?? null),
            'diagnostico' => $this->nullableString($input['diagnostico'] ?? null, 400),
            'cie10' => $cie10,
            'tipo_alta' => $tipoAlta,
            'cirugia' => filter_var($input['cirugia'] ?? false, FILTER_VALIDATE_BOOLEAN),
            'tipo_cirugia' => $this->nullableString($input['tipo_cirugia'] ?? null, 150),
            'recien_nacido' => filter_var($input['recien_nacido'] ?? false, FILTER_VALIDATE_BOOLEAN),
            'cesarea' => filter_var($input['cesarea'] ?? false, FILTER_VALIDATE_BOOLEAN),
        ];
    }

    private function assertEstablishment(int $establecimientoId, User $user): void
    {
        $establecimiento = Establecimiento::find($establecimientoId);
        if (! $establecimiento) {
            throw ValidationException::withMessages(['establecimiento_id' => 'El establecimiento no existe.']);
        }
        if (! Record::userHasGlobalAccess($user)
            && ! in_array($establecimientoId, Record::assignedEstablishmentIds($user), true)) {
            throw ValidationException::withMessages(['establecimiento_id' => 'No tiene alcance sobre este establecimiento.']);
        }
    }

    private function sp10(): Formulario
    {
        $formulario = Formulario::where('codigo', 'SP10')->first();
        if (! $formulario) {
            throw ValidationException::withMessages(['formulario' => 'El formulario SP10 no está configurado.']);
        }

        return $formulario;
    }

    /**
     * @param  array<string, mixed>  $metrics
     * @return array<string, mixed>
     */
    private function toRecordValues(Formulario $formulario, array $metrics): array
    {
        $fields = $formulario->secciones()->with('fields.detalle.prestaciones')->get()->flatMap->fields->keyBy('code');
        $values = [];
        foreach ([
            'ingresos_total', 'egresos_total', 'dias_estancia', 'fallecidos',
            'cirugias', 'cesareas', 'partos', 'recien_nacidos',
        ] as $code) {
            if ($fields->has($code)) {
                $values[$code] = $metrics[$code];
            }
        }
        if ($fields->has('egresos_por_servicio')) {
            $values['egresos_por_servicio'] = [
                'rows' => $this->tablaRows($fields->get('egresos_por_servicio'), $metrics['egresos_por_servicio']),
            ];
        }
        if ($fields->has('egresos_por_sexo')) {
            $values['egresos_por_sexo'] = [
                'rows' => $this->tablaRows($fields->get('egresos_por_sexo'), $metrics['egresos_por_sexo']),
            ];
        }

        return $values;
    }

    /**
     * @param  array<string, int>  $counts
     * @return array<string, array<string, int>>
     */
    private function tablaRows($field, array $counts): array
    {
        $items = $field->rowItems();
        $labels = $field->code === 'egresos_por_sexo'
            ? ['M' => 'Masculino', 'F' => 'Femenino']
            : HospEpisodio::SERVICIOS;
        $rows = [];
        foreach ($counts as $code => $count) {
            if (! $code) {
                continue;
            }
            $wanted = $labels[$code] ?? $code;
            $item = $items->first(fn ($row) => strcasecmp((string) $row->label, (string) $wanted) === 0);
            if (! $item) {
                continue;
            }
            $rows[(string) $item->id] = ['total' => (int) $count];
        }

        return $rows;
    }

    /**
     * @return array{pacientes_dia:int,camas_operativas:int,camas_disponibles:int}
     */
    private function sp11Totals(int $establecimientoId, int $year, int $month, ?int $servicioId = null): array
    {
        $formulario = Formulario::where('codigo', 'SP11')->first();
        $empty = ['pacientes_dia' => 0, 'camas_operativas' => 0, 'camas_disponibles' => 0];
        if (! $formulario) {
            return $empty;
        }
        $records = Record::query()
            ->where('formulario_id', $formulario->id)
            ->where('establecimiento_id', $establecimientoId)
            ->where('periodo_anio', $year)
            ->where('periodo_mes', $month)
            ->when(
                $servicioId,
                fn ($query) => $query->where('estructura_servicio_id', $servicioId),
                fn ($query) => $query
            )
            ->with('values.field')
            ->get();

        $totals = $empty;
        foreach ($records as $record) {
            $matrix = $record->values->first(fn ($value) => $value->field?->code === 'paciente_dia');
            $payload = $matrix?->value_json ?? [];
            $totals['pacientes_dia'] += Sp11Matrix::total($payload, 'pacientes_dia');
            $totals['camas_operativas'] += Sp11Matrix::total($payload, 'camas_operativas');
            $totals['camas_disponibles'] += Sp11Matrix::total($payload, 'camas_disponibles');
        }

        return $totals;
    }

    /**
     * @return array{from:string,to:string}
     */
    private function monthRange(int $year, int $month): array
    {
        $start = CarbonImmutable::create($year, $month, 1);

        return [
            'from' => $start->toDateString(),
            'to' => $start->endOfMonth()->toDateString(),
        ];
    }

    private function nullableString(mixed $value, int $max): ?string
    {
        $value = trim((string) $value);

        return $value === '' ? null : mb_substr($value, 0, $max);
    }

    private function periodKey(int $establecimientoId, int $year, int $month): string
    {
        return "{$establecimientoId}:{$year}:{$month}";
    }
}
