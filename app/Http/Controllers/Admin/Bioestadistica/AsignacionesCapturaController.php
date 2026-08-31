<?php

namespace App\Http\Controllers\Admin\Bioestadistica;

use App\Application\Bioestadistica\Audit\AuditService;
use App\Http\Controllers\Controller;
use App\Models\Bioestadistica\Establecimiento;
use App\Models\Bioestadistica\Formulario;
use App\Models\Bioestadistica\UsuarioCapturaAsignacion;
use App\Models\Bioestadistica\UsuarioEstablecimiento;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AsignacionesCapturaController extends Controller
{
    public function index(Request $request): View
    {
        $query = UsuarioCapturaAsignacion::query()
            ->with(['user', 'formulario', 'establecimiento.distrito.departamento', 'asignadoPor'])
            ->orderByDesc('updated_at');

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->integer('user_id'));
        }
        if ($request->filled('formulario_id')) {
            $formularioId = $request->integer('formulario_id');
            if ($formularioId === -1) {
                $query->whereNull('formulario_id');
            } else {
                $query->where('formulario_id', $formularioId);
            }
        }
        if ($request->filled('establecimiento_id')) {
            $query->where('establecimiento_id', $request->integer('establecimiento_id'));
        }
        if ($request->filled('activo')) {
            $query->where('activo', $request->boolean('activo'));
        }

        return view('admin.bioestadistica.asignaciones.index', [
            'asignaciones' => $query->paginate(25)->withQueryString(),
            'digitadores' => User::role('Digitador Bioestadística')->orderBy('name')->get(),
            'formularios' => Formulario::where('estado', 'activo')->ordenSp()->get(),
            'establecimientos' => Establecimiento::with('distrito.departamento')->orderBy('nombre')->get(),
            'legacyAssignments' => UsuarioEstablecimiento::query()
                ->get()
                ->groupBy('user_id')
                ->map(fn ($rows) => $rows->pluck('establecimiento_id')->all()),
            'filters' => $request->only(['user_id', 'formulario_id', 'establecimiento_id', 'activo']),
        ]);
    }

    public function store(Request $request, AuditService $audit): RedirectResponse
    {
        $data = $this->validated($request);
        $this->assertDigitador($data['user_id']);
        $this->assertNoDuplicate($data);

        $assignment = UsuarioCapturaAsignacion::create($data + [
            'asignado_por' => $request->user()->id,
            'activo' => true,
        ]);

        $audit->recordExplicit('create', UsuarioCapturaAsignacion::class, (int) $assignment->id, [], $assignment->only([
            'user_id', 'formulario_id', 'establecimiento_id', 'activo',
        ]), ['phase' => 'assignment']);

        return back()->with('success', 'Asignación creada.');
    }

    public function update(Request $request, UsuarioCapturaAsignacion $asignacion, AuditService $audit): RedirectResponse
    {
        $previous = $asignacion->only(['user_id', 'formulario_id', 'establecimiento_id', 'activo']);
        $data = $this->validated($request, $asignacion->id);
        $this->assertDigitador($data['user_id']);
        $this->assertNoDuplicate($data, $asignacion->id);

        $asignacion->update($data + ['asignado_por' => $request->user()->id]);

        $audit->recordExplicit('update', UsuarioCapturaAsignacion::class, (int) $asignacion->id, $previous, $asignacion->only([
            'user_id', 'formulario_id', 'establecimiento_id', 'activo',
        ]), ['phase' => 'assignment']);

        return back()->with('success', 'Asignación actualizada.');
    }

    public function destroy(UsuarioCapturaAsignacion $asignacion, AuditService $audit): RedirectResponse
    {
        $previous = $asignacion->only(['user_id', 'formulario_id', 'establecimiento_id', 'activo']);
        $id = (int) $asignacion->id;
        $asignacion->delete();

        $audit->recordExplicit('delete', UsuarioCapturaAsignacion::class, $id, $previous, [], ['phase' => 'assignment']);

        return back()->with('success', 'Asignación eliminada.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?int $ignoreId = null): array
    {
        $data = $request->validate([
            'user_id' => ['required', 'integer', Rule::exists(User::class, 'id')],
            'formulario_id' => ['nullable', 'integer', Rule::exists(Formulario::class, 'id')->where('estado', 'activo')->withoutTrashed()],
            'establecimiento_id' => ['required', 'integer', Rule::exists(Establecimiento::class, 'id')->withoutTrashed()],
            'activo' => ['sometimes', 'boolean'],
        ]);

        $data['formulario_id'] = filled($data['formulario_id'] ?? null) ? (int) $data['formulario_id'] : null;
        $data['activo'] = $request->boolean('activo', true);

        return $data;
    }

    private function assertDigitador(int $userId): void
    {
        $user = User::findOrFail($userId);
        abort_unless($user->hasRole('Digitador Bioestadística'), 422, 'El usuario debe tener el rol Digitador Bioestadística.');
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function assertNoDuplicate(array $data, ?int $ignoreId = null): void
    {
        if (! ($data['activo'] ?? true)) {
            return;
        }

        $base = UsuarioCapturaAsignacion::query()
            ->where('user_id', $data['user_id'])
            ->where('establecimiento_id', $data['establecimiento_id'])
            ->where('activo', true);

        if ($ignoreId !== null) {
            $base->where('id', '!=', $ignoreId);
        }

        if ($data['formulario_id'] === null) {
            abort_if($base->exists(), 422, 'Ya existe una asignación activa para ese digitador y establecimiento.');

            return;
        }

        $existsAll = (clone $base)->whereNull('formulario_id')->exists();
        $existsSame = (clone $base)->where('formulario_id', $data['formulario_id'])->exists();
        abort_if(
            $existsAll || $existsSame,
            422,
            'Ya existe una asignación equivalente o más amplia para ese digitador y establecimiento.'
        );
    }
}
