<?php

namespace App\Application\Bioestadistica\Audit;

use App\Models\Bioestadistica\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Throwable;

class AuditService
{
    private int $muted = 0;

    private ?string $correlationId = null;

    private ?string $batchId = null;

    public function __construct(private AuditRedactor $redactor)
    {
    }

    public function withoutAuditing(callable $callback): mixed
    {
        $this->muted++;
        try {
            return $callback();
        } finally {
            $this->muted = max(0, $this->muted - 1);
        }
    }

    public function isMuted(): bool
    {
        return $this->muted > 0;
    }

    public function beginBatch(?string $batchId = null): string
    {
        $this->batchId = $batchId ?: (string) Str::uuid();

        return $this->batchId;
    }

    public function endBatch(): void
    {
        $this->batchId = null;
    }

    public function recordModel(string $accion, Model $model, ?array $old = null, ?array $new = null, array $metadata = []): ?AuditLog
    {
        if ($this->isMuted()) {
            return null;
        }

        $oldPayload = $old === null ? null : $this->redactor->redact($old, $model, $new);
        $newPayload = $new === null ? null : $this->redactor->redact($new, $model, $old);

        if ($accion === 'update' && ($oldPayload === [] || $oldPayload === null) && ($newPayload === [] || $newPayload === null)) {
            return null;
        }

        return $this->write(
            $accion,
            $model::class,
            $model->getKey(),
            $oldPayload,
            $newPayload,
            $metadata
        );
    }

    public function recordExplicit(
        string $accion,
        string $entityType,
        ?int $entityId,
        ?array $old = null,
        ?array $new = null,
        array $metadata = []
    ): ?AuditLog {
        if ($this->isMuted()) {
            return null;
        }

        return $this->write(
            $accion,
            $entityType,
            $entityId,
            $old === null ? null : $this->redactor->scrubArray($old),
            $new === null ? null : $this->redactor->scrubArray($new),
            $metadata
        );
    }

    /**
     * @param  array<string, mixed>  $counts
     * @param  array<string, mixed>  $filters
     */
    public function recordBatch(
        string $accion,
        string $entityType,
        ?int $entityId,
        array $counts,
        array $filters = [],
        array $metadata = []
    ): ?AuditLog {
        return $this->recordExplicit($accion, $entityType, $entityId, null, [
            'counts' => $this->safeCounts($counts),
            'filters' => $this->redactor->scrubArray($filters),
        ], $metadata);
    }

    /**
     * @param  array<string, mixed>|null  $old
     * @param  array<string, mixed>|null  $new
     * @param  array<string, mixed>  $metadata
     */
    private function write(
        string $accion,
        string $entityType,
        mixed $entityId,
        ?array $old,
        ?array $new,
        array $metadata
    ): ?AuditLog {
        if (! in_array($accion, AuditLog::ACTIONS, true)) {
            $metadata['phase'] = $metadata['phase'] ?? $accion;
            $accion = $this->canonicalAction($accion);
        }

        $context = $this->requestContext();
        $payload = [
            'user_id' => Auth::id(),
            'accion' => $accion,
            'entity_type' => $entityType,
            'entity_id' => $entityId !== null ? (int) $entityId : null,
            'old_values' => $old,
            'new_values' => $new,
            'ip' => $context['ip'],
            'user_agent' => $context['user_agent'],
            'metadata' => array_filter([
                'correlation_id' => $this->correlationId(),
                'batch_id' => $this->batchId,
                ...$this->redactor->scrubArray($metadata),
            ], fn ($value) => $value !== null && $value !== []),
            'created_at' => now(),
        ];

        try {
            return AuditLog::create($payload);
        } catch (Throwable $exception) {
            report($exception);

            return null;
        }
    }

    /**
     * @return array{ip: ?string, user_agent: ?string}
     */
    private function requestContext(): array
    {
        $request = request();
        if (! $request) {
            return ['ip' => null, 'user_agent' => null];
        }

        $ip = $request->ip();
        if ($ip !== null && ! filter_var($ip, FILTER_VALIDATE_IP)) {
            $ip = null;
        }

        return [
            'ip' => $ip,
            'user_agent' => Str::limit((string) $request->userAgent(), 500, ''),
        ];
    }

    private function correlationId(): string
    {
        return $this->correlationId ??= (string) Str::uuid();
    }

    private function canonicalAction(string $accion): string
    {
        return match ($accion) {
            'execute', 'commit', 'cancel', 'consolidate' => 'import',
            'archive' => 'delete',
            default => 'update',
        };
    }

    /**
     * @param  array<string, mixed>  $counts
     * @return array<string, int|float>
     */
    private function safeCounts(array $counts): array
    {
        $safe = [];
        foreach ($counts as $key => $value) {
            if (is_int($value) || is_float($value)) {
                $safe[$key] = $value;
            }
        }

        return $safe;
    }
}
