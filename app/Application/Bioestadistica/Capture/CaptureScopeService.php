<?php

namespace App\Application\Bioestadistica\Capture;

use App\Models\Bioestadistica\Formulario;
use App\Models\Bioestadistica\UsuarioCapturaAsignacion;
use App\Models\Bioestadistica\UsuarioEstablecimiento;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class CaptureScopeService
{
    public function userHasGlobalAccess(User $user): bool
    {
        if ($user->hasAnyRole([
            'Administrador',
            'Analista de Bioestadística',
            'Consultor Bioestadística',
            'Auditor Bioestadística',
        ])) {
            return true;
        }

        // Digitador sin establecimientos/asignaciones definidas → puede operar en todos
        if ($user->hasRole('Digitador Bioestadística') && ! $this->hasExplicitEstablishmentScope($user)) {
            return true;
        }

        return false;
    }

    /**
     * Tiene alcance acotado por asignación granular o por vínculo usuario-establecimiento.
     */
    public function hasExplicitEstablishmentScope(User $user): bool
    {
        if (UsuarioCapturaAsignacion::query()
            ->where('user_id', $user->id)
            ->where('activo', true)
            ->exists()) {
            return true;
        }

        return UsuarioEstablecimiento::query()
            ->where('user_id', $user->id)
            ->exists();
    }

    public function usesGranularAssignments(User $user): bool
    {
        if ($this->userHasGlobalAccess($user)) {
            return false;
        }

        return UsuarioCapturaAsignacion::query()
            ->where('user_id', $user->id)
            ->where('activo', true)
            ->exists();
    }

    /**
     * @return array<int, int>
     */
    public function assignedEstablishmentIds(User $user): array
    {
        if ($this->userHasGlobalAccess($user)) {
            return [];
        }

        if ($this->usesGranularAssignments($user)) {
            return UsuarioCapturaAsignacion::query()
                ->where('user_id', $user->id)
                ->where('activo', true)
                ->distinct()
                ->pluck('establecimiento_id')
                ->map(fn ($id) => (int) $id)
                ->all();
        }

        return UsuarioEstablecimiento::query()
            ->where('user_id', $user->id)
            ->pluck('establecimiento_id')
            ->map(fn ($id) => (int) $id)
            ->all();
    }

    /**
     * @return Collection<int, Formulario>
     */
    public function allowedFormularios(User $user, ?int $establecimientoId = null): Collection
    {
        $query = Formulario::query()->where('estado', 'activo')->ordenSp();

        if ($this->userHasGlobalAccess($user)) {
            return $query->get();
        }

        if (! $this->usesGranularAssignments($user)) {
            return $query->get();
        }

        $assignments = UsuarioCapturaAsignacion::query()
            ->where('user_id', $user->id)
            ->where('activo', true)
            ->when($establecimientoId !== null, fn ($builder) => $builder->where('establecimiento_id', $establecimientoId))
            ->get();

        if ($assignments->isEmpty()) {
            return collect();
        }

        if ($assignments->contains(fn (UsuarioCapturaAsignacion $row) => $row->formulario_id === null)) {
            return $query->get();
        }

        $formIds = $assignments
            ->pluck('formulario_id')
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();

        if ($formIds === []) {
            return collect();
        }

        return $query->whereIn('id', $formIds)->get();
    }

    public function canCapture(User $user, int $formularioId, int $establecimientoId): bool
    {
        if ($this->userHasGlobalAccess($user)) {
            return true;
        }

        if (! in_array($establecimientoId, $this->assignedEstablishmentIds($user), true)) {
            return false;
        }

        if (! $this->usesGranularAssignments($user)) {
            return true;
        }

        $assignments = UsuarioCapturaAsignacion::query()
            ->where('user_id', $user->id)
            ->where('activo', true)
            ->where('establecimiento_id', $establecimientoId)
            ->get();

        if ($assignments->isEmpty()) {
            return false;
        }

        if ($assignments->contains(fn (UsuarioCapturaAsignacion $row) => $row->formulario_id === null)) {
            return true;
        }

        return $assignments->contains(fn (UsuarioCapturaAsignacion $row) => (int) $row->formulario_id === $formularioId);
    }

    public function assertCanCapture(User $user, int $formularioId, int $establecimientoId): void
    {
        if ($this->canCapture($user, $formularioId, $establecimientoId)) {
            return;
        }

        abort(403, 'No tiene asignado este formulario en el establecimiento seleccionado.');
    }

    public function validateCanCapture(User $user, int $formularioId, int $establecimientoId, string $field = 'formulario_id'): void
    {
        if ($this->canCapture($user, $formularioId, $establecimientoId)) {
            return;
        }

        throw ValidationException::withMessages([
            $field => 'No tiene asignado este formulario en el establecimiento seleccionado.',
        ]);
    }

    public function assertCanUseEstablecimiento(User $user, ?int $establecimientoId): void
    {
        if ($establecimientoId === null || $this->userHasGlobalAccess($user)) {
            return;
        }

        if (! in_array($establecimientoId, $this->assignedEstablishmentIds($user), true)) {
            throw ValidationException::withMessages([
                'establecimiento_id' => 'No tiene asignado el establecimiento seleccionado.',
            ]);
        }
    }

    /**
     * @param  Builder<\App\Models\Bioestadistica\Record>  $query
     * @return Builder<\App\Models\Bioestadistica\Record>
     */
    public function applyRecordScope(Builder $query, User $user): Builder
    {
        if ($this->userHasGlobalAccess($user)) {
            return $query;
        }

        if (! $this->usesGranularAssignments($user)) {
            return $query->whereIn('establecimiento_id', $this->assignedEstablishmentIds($user));
        }

        $assignments = UsuarioCapturaAsignacion::query()
            ->where('user_id', $user->id)
            ->where('activo', true)
            ->get();

        if ($assignments->isEmpty()) {
            return $query->whereRaw('1 = 0');
        }

        return $query->where(function (Builder $outer) use ($assignments) {
            foreach ($assignments as $assignment) {
                $outer->orWhere(function (Builder $inner) use ($assignment) {
                    $inner->where('establecimiento_id', $assignment->establecimiento_id);
                    if ($assignment->formulario_id !== null) {
                        $inner->where('formulario_id', $assignment->formulario_id);
                    }
                });
            }
        });
    }

    public function hasAnyCaptureScope(User $user): bool
    {
        if ($this->userHasGlobalAccess($user)) {
            return true;
        }

        if ($this->usesGranularAssignments($user)) {
            return UsuarioCapturaAsignacion::query()
                ->where('user_id', $user->id)
                ->where('activo', true)
                ->exists();
        }

        return UsuarioEstablecimiento::query()->where('user_id', $user->id)->exists();
    }
}
