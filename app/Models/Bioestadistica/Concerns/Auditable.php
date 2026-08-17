<?php

namespace App\Models\Bioestadistica\Concerns;

use App\Observers\Bioestadistica\AuditObserver;

trait Auditable
{
    public static function bootAuditable(): void
    {
        static::observe(AuditObserver::class);
    }

    /**
     * @return array<int, string>
     */
    public function auditSensitiveAttributes(): array
    {
        return $this->auditSensitive ?? [];
    }

    /**
     * @return array<int, string>
     */
    public function auditExcludedAttributes(): array
    {
        return $this->auditExclude ?? [];
    }
}
