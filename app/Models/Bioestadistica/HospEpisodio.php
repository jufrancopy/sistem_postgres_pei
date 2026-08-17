<?php

namespace App\Models\Bioestadistica;

use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class HospEpisodio extends BioestadisticaModel
{
    public const SEXO = ['M', 'F'];

    public const SERVICIOS = [
        'CLINICA_MEDICA' => 'Clínica médica',
        'CIRUGIA' => 'Cirugía',
        'MATERNIDAD' => 'Maternidad',
        'UTI' => 'UTI',
        'PEDIATRIA' => 'Pediatría',
        'OTRO' => 'Otro',
    ];

    public const TIPOS_ALTA = [
        'MEJORADO' => 'Mejorado',
        'CURADO' => 'Curado',
        'TRASLADO' => 'Traslado',
        'FALLECIDO' => 'Fallecido',
        'RETIRO_VOLUNTARIO' => 'Retiro voluntario',
    ];

    public const TIPOS_CIRUGIA = [
        'MAYOR' => 'Mayor',
        'MENOR' => 'Menor',
        'ALTA_COMPLEJIDAD' => 'Alta complejidad',
    ];

    protected $table = 'bioestadistica.hosp_episodios';

    protected $hidden = ['cedula', 'cedula_hash'];

    protected array $auditSensitive = [
        'cedula', 'cedula_hash', 'source_fingerprint', 'diagnostico', 'cie10', 'seguro', 'source_row',
    ];

    protected $casts = [
        'cedula' => 'encrypted',
        'fecha_ingreso' => 'date',
        'fecha_egreso' => 'date',
        'cirugia' => 'boolean',
        'recien_nacido' => 'boolean',
        'cesarea' => 'boolean',
        'periodo_anio' => 'integer',
        'periodo_mes' => 'integer',
        'edad' => 'integer',
    ];

    public function establecimiento(): BelongsTo
    {
        return $this->belongsTo(Establecimiento::class);
    }

    public function record(): BelongsTo
    {
        return $this->belongsTo(Record::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeForUser(Builder $query, User $user): Builder
    {
        if (Record::userHasGlobalAccess($user)) {
            return $query;
        }

        return $query->whereIn('establecimiento_id', Record::assignedEstablishmentIds($user));
    }

    public function isAccessibleBy(User $user): bool
    {
        return Record::userHasGlobalAccess($user)
            || in_array((int) $this->establecimiento_id, Record::assignedEstablishmentIds($user), true);
    }

    public function stayDays(): ?int
    {
        return self::calculateStayDays($this->fecha_ingreso, $this->fecha_egreso);
    }

    public function visibleCedula(User $user): ?string
    {
        $cedula = $this->cedula;
        if ($cedula === null || $cedula === '') {
            return null;
        }

        return $user->can('bio.hosp.view_pii') ? $cedula : self::maskCedula($cedula);
    }

    public static function calculateStayDays(mixed $ingreso, mixed $egreso): ?int
    {
        if (! $ingreso || ! $egreso) {
            return null;
        }
        $start = CarbonImmutable::parse($ingreso)->startOfDay();
        $end = CarbonImmutable::parse($egreso)->startOfDay();
        if ($end->lt($start)) {
            return null;
        }

        return max(1, $start->diffInDays($end));
    }

    public static function periodFromDates(mixed $ingreso, mixed $egreso): array
    {
        $date = CarbonImmutable::parse($egreso ?: $ingreso);

        return [
            'periodo_anio' => (int) $date->year,
            'periodo_mes' => (int) $date->month,
        ];
    }

    public static function normalizeCedula(?string $cedula): ?string
    {
        if ($cedula === null) {
            return null;
        }
        $digits = preg_replace('/\D+/', '', $cedula) ?? '';
        $value = $digits !== '' ? $digits : trim($cedula);

        return $value === '' ? null : $value;
    }

    public static function hashCedula(?string $cedula): ?string
    {
        $normalized = self::normalizeCedula($cedula);
        if ($normalized === null) {
            return null;
        }

        return hash_hmac('sha256', $normalized, (string) config('app.key'));
    }

    public static function maskCedula(?string $cedula): ?string
    {
        $normalized = self::normalizeCedula($cedula);
        if ($normalized === null) {
            return null;
        }
        $visible = substr($normalized, -3);
        $hidden = max(0, strlen($normalized) - 3);

        return str_repeat('•', $hidden).$visible;
    }

    public static function normalizeSexo(?string $sexo): ?string
    {
        if ($sexo === null || trim($sexo) === '') {
            return null;
        }
        $key = Str::upper(Str::ascii(trim($sexo)));
        if (in_array($key, ['M', 'MASCULINO', 'HOMBRE', 'H'], true)) {
            return 'M';
        }
        if (in_array($key, ['F', 'FEMENINO', 'MUJER'], true)) {
            return 'F';
        }

        return null;
    }

    public static function normalizeServicio(?string $servicio): ?string
    {
        if ($servicio === null || trim($servicio) === '') {
            return null;
        }
        $key = Str::upper(Str::slug(Str::ascii($servicio), '_'));
        foreach (array_keys(self::SERVICIOS) as $code) {
            if ($key === $code || str_contains($key, Str::lower($code)) || str_contains($code, $key)) {
                return $code;
            }
        }
        $aliases = [
            'CLINICA' => 'CLINICA_MEDICA',
            'MEDICINA' => 'CLINICA_MEDICA',
            'QUIROFANO' => 'CIRUGIA',
            'GINECO' => 'MATERNIDAD',
            'OBSTETRICIA' => 'MATERNIDAD',
            'UCI' => 'UTI',
            'INTENSIVA' => 'UTI',
            'NINOS' => 'PEDIATRIA',
            'NINAS' => 'PEDIATRIA',
        ];
        foreach ($aliases as $needle => $code) {
            if (str_contains($key, $needle)) {
                return $code;
            }
        }

        return 'OTRO';
    }

    public static function normalizeTipoAlta(?string $tipo): ?string
    {
        if ($tipo === null || trim($tipo) === '') {
            return null;
        }
        $key = Str::upper(Str::slug(Str::ascii($tipo), '_'));
        foreach (array_keys(self::TIPOS_ALTA) as $code) {
            if ($key === $code) {
                return $code;
            }
        }
        $aliases = [
            'MUERTE' => 'FALLECIDO',
            'OBITO' => 'FALLECIDO',
            'DEFUNCION' => 'FALLECIDO',
            'FALLEC' => 'FALLECIDO',
            'TRASLAD' => 'TRASLADO',
            'REFERIDO' => 'TRASLADO',
            'VOLUNT' => 'RETIRO_VOLUNTARIO',
            'ALTA_VOLUNTARIA' => 'RETIRO_VOLUNTARIO',
            'CURAC' => 'CURADO',
            'MEJOR' => 'MEJORADO',
        ];
        foreach ($aliases as $needle => $code) {
            if (str_contains($key, $needle)) {
                return $code;
            }
        }

        return null;
    }

    public static function normalizeCie10(?string $code): ?string
    {
        if ($code === null || trim($code) === '') {
            return null;
        }
        $value = Str::upper(preg_replace('/\s+/', '', trim($code)) ?? '');
        if ($value === '') {
            return null;
        }

        return $value;
    }

    public static function isValidCie10(?string $code): bool
    {
        if ($code === null || $code === '') {
            return true;
        }

        return (bool) preg_match('/^[A-Z][0-9]{2}(?:\.[0-9A-Z]{1,4})?$/', $code);
    }

    public static function fingerprint(array $data): string
    {
        $parts = [
            $data['establecimiento_id'] ?? '',
            self::hashCedula($data['cedula'] ?? null) ?? '',
            (string) ($data['fecha_ingreso'] ?? ''),
            (string) ($data['fecha_egreso'] ?? ''),
            (string) ($data['servicio'] ?? ''),
            (string) ($data['cie10'] ?? ''),
        ];

        return hash('sha256', implode('|', $parts));
    }
}
