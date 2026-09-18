<?php

namespace App\Application\Bioestadistica\Forms;

/**
 * Modo de columnas métricas según establecimientos.prestador
 * (+ flag incluye_tercerizado para IPS con producción tercerizada).
 *
 *   TERCERIZADO → Tercerizado + Total
 *   CONVENIO    → IPS + Convenio + Total
 *   IPS         → solo Total
 */
final class PrestadorMetricMode
{
    public const MODE_IPS = 'ips_total';

    public const MODE_CONVENIO = 'convenio_split';

    public const MODE_TERCERIZADO = 'tercerizado';

    public const SERIES = ['ips', 'convenio', 'tercerizado'];

    public static function normalizePrestador(?string $prestador): string
    {
        $p = mb_strtoupper(trim((string) $prestador));
        $p = strtr($p, [
            'Á' => 'A', 'É' => 'E', 'Í' => 'I', 'Ó' => 'O', 'Ú' => 'U', 'Ñ' => 'N',
        ]);
        if ($p === '') {
            return 'IPS';
        }
        if (str_contains($p, 'TERCER')) {
            return 'TERCERIZADO';
        }
        if (str_contains($p, 'CONV')) {
            return 'CONVENIO';
        }

        return 'IPS';
    }

    public static function mode(?string $prestador, bool $incluyeTercerizado = false): string
    {
        return match (self::normalizePrestador($prestador)) {
            'TERCERIZADO' => self::MODE_TERCERIZADO,
            'CONVENIO' => self::MODE_CONVENIO,
            default => $incluyeTercerizado ? self::MODE_TERCERIZADO : self::MODE_IPS,
        };
    }

    /**
     * @param  array<string, mixed>  $config
     */
    public static function appliesTo(array $config): bool
    {
        if (! empty($config['metric_by_prestador'])) {
            return true;
        }

        $codes = collect($config['columns'] ?? [])->pluck('code')->all();

        return count(array_intersect($codes, self::SERIES)) > 0;
    }

    public static function isSeriesCode(string $code): bool
    {
        return in_array($code, self::SERIES, true);
    }

    /**
     * @param  array<string, mixed>  $config
     */
    public static function totalCode(array $config): string
    {
        if (is_array($config['row_total'] ?? null) && ! empty($config['row_total']['code'])) {
            return (string) $config['row_total']['code'];
        }

        $codes = collect($config['columns'] ?? [])->pluck('code');
        if ($codes->contains('total_consultas')) {
            return 'total_consultas';
        }

        return 'total';
    }

    /**
     * @return list<string>
     */
    public static function visibleCodes(?string $prestador, string $totalCode = 'total', bool $incluyeTercerizado = false): array
    {
        return match (self::mode($prestador, $incluyeTercerizado)) {
            self::MODE_TERCERIZADO => ['tercerizado', $totalCode],
            self::MODE_CONVENIO => ['ips', 'convenio', $totalCode],
            default => [$totalCode],
        };
    }

    /**
     * @return list<string>
     */
    public static function sumColumns(?string $prestador, bool $incluyeTercerizado = false): array
    {
        return match (self::mode($prestador, $incluyeTercerizado)) {
            self::MODE_TERCERIZADO => ['tercerizado'],
            self::MODE_CONVENIO => ['ips', 'convenio'],
            default => [],
        };
    }

    /**
     * @param  array<string, mixed>  $config
     * @return array<string, mixed>
     */
    public static function filterConfig(array $config, ?string $prestador, bool $incluyeTercerizado = false): array
    {
        if (! self::appliesTo($config)) {
            return $config;
        }

        $totalCode = self::totalCode($config);
        $visible = self::visibleCodes($prestador, $totalCode, $incluyeTercerizado);

        $config['columns'] = collect($config['columns'] ?? [])
            ->filter(fn ($column) => in_array($column['code'] ?? '', $visible, true))
            ->map(function ($column) use ($prestador, $incluyeTercerizado) {
                // IPS + flag: distinguir de prestador tipo Tercerizado.
                if (($column['code'] ?? '') === 'tercerizado'
                    && self::normalizePrestador($prestador) === 'IPS'
                    && $incluyeTercerizado) {
                    $column['label'] = 'Servicio Tercerizado';
                }

                return $column;
            })
            ->values()
            ->all();

        $sums = self::sumColumns($prestador, $incluyeTercerizado);
        if ($sums === []) {
            unset($config['row_total'], $config['row_totals']);
        } else {
            $config['row_total'] = [
                'code' => $totalCode,
                'sum_columns' => $sums,
            ];
            unset($config['row_totals']);
        }

        return $config;
    }

    public static function helpText(?string $prestador = null, bool $incluyeTercerizado = false): string
    {
        $isIpsServicio = self::normalizePrestador($prestador) === 'IPS' && $incluyeTercerizado;

        return match (self::mode($prestador, $incluyeTercerizado)) {
            self::MODE_TERCERIZADO => $isIpsServicio
                ? 'Informe la producción en Servicio Tercerizado; el Total se calcula solo. Las filas sin actividad pueden quedar vacías.'
                : 'Informe la producción en Tercerizado; el Total se calcula solo. Las filas sin actividad pueden quedar vacías.',
            self::MODE_CONVENIO => 'Informe IPS y/o Convenio por fila; el Total se calcula solo. Las filas sin actividad pueden quedar vacías.',
            default => 'Informe el Total por fila. Las filas sin actividad pueden quedar vacías.',
        };
    }
}
