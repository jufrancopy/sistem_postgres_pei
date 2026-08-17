<?php

namespace App\Application\Bioestadistica\Audit;

use App\Models\Bioestadistica\HospEpisodio;
use App\Models\Bioestadistica\ImportJob;
use App\Models\Bioestadistica\RecordValue;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AuditRedactor
{
    public const EXCLUDED = [
        'updated_at',
        'remember_token',
        'created_at',
        'deleted_at',
    ];

    public const SENSITIVE = [
        'cedula',
        'cedula_hash',
        'password',
        'password_confirmation',
        'remember_token',
        'token',
        'api_token',
        'source_fingerprint',
        'fingerprint',
        'archivo_path',
        'archivo',
        'checksum',
        'diagnostico',
        'cie10',
        'seguro',
        'hash',
        'source_row',
    ];

    public const RECORD_VALUE_SENSITIVE = [
        'value_text',
        'value_date',
        'value_json',
    ];

    /**
     * @param  array<string, mixed>  $attributes
     * @param  array<string, mixed>|null  $compare
     * @return array<string, mixed>
     */
    public function redact(array $attributes, Model $model, ?array $compare = null): array
    {
        $payload = [];
        foreach ($attributes as $key => $value) {
            if ($this->isExcluded($key, $model)) {
                continue;
            }
            if ($this->isSensitive($key, $model)) {
                $changed = $compare === null || ! $this->same($value, $compare[$key] ?? null);
                $payload[$key] = ['redacted' => true, 'changed' => $changed];
                continue;
            }
            $payload[$key] = $this->normalize($value);
        }

        return $payload;
    }

    public function isExcluded(string $key, Model $model): bool
    {
        $custom = method_exists($model, 'auditExcludedAttributes')
            ? $model->auditExcludedAttributes()
            : [];

        return in_array($key, array_merge(self::EXCLUDED, $custom), true);
    }

    public function isSensitive(string $key, Model $model): bool
    {
        $custom = method_exists($model, 'auditSensitiveAttributes')
            ? $model->auditSensitiveAttributes()
            : [];
        $keys = array_merge(self::SENSITIVE, $custom);
        if ($model instanceof RecordValue) {
            $keys = array_merge($keys, self::RECORD_VALUE_SENSITIVE);
        }
        if ($model instanceof HospEpisodio) {
            $keys = array_merge($keys, ['cedula', 'cedula_hash', 'source_fingerprint', 'diagnostico', 'cie10', 'seguro']);
        }
        if ($model instanceof ImportJob) {
            $keys = array_merge($keys, ['archivo_path', 'checksum', 'error']);
        }

        $needle = Str::lower($key);

        return in_array($key, $keys, true)
            || str_contains($needle, 'cedula')
            || str_contains($needle, 'token')
            || str_contains($needle, 'fingerprint')
            || str_contains($needle, 'password')
            || str_ends_with($needle, '_hash')
            || str_ends_with($needle, '_path');
    }

    public function same(mixed $left, mixed $right): bool
    {
        return $this->normalize($left) === $this->normalize($right);
    }

    public function normalize(mixed $value): mixed
    {
        if ($value instanceof DateTimeInterface) {
            return $value->format('Y-m-d H:i:s');
        }
        if (is_array($value)) {
            return $this->scrubArray($value);
        }
        if (is_object($value) && method_exists($value, 'toArray')) {
            return $this->scrubArray($value->toArray());
        }

        return $value;
    }

    /**
     * @param  array<string, mixed>  $values
     * @return array<string, mixed>
     */
    public function scrubArray(array $values): array
    {
        $clean = [];
        foreach ($values as $key => $value) {
            if (is_string($key) && $this->looksSensitiveKey($key)) {
                $clean[$key] = ['redacted' => true, 'changed' => true];
                continue;
            }
            $clean[$key] = is_array($value) ? $this->scrubArray($value) : $this->normalize($value);
        }

        return $clean;
    }

    public function looksSensitiveKey(string $key): bool
    {
        $needle = Str::lower($key);

        return in_array($needle, self::SENSITIVE, true)
            || str_contains($needle, 'cedula')
            || str_contains($needle, 'token')
            || str_contains($needle, 'fingerprint')
            || str_contains($needle, 'password')
            || str_contains($needle, 'diagnostico')
            || str_ends_with($needle, '_hash')
            || str_ends_with($needle, '_path');
    }
}
