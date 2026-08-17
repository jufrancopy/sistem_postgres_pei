<?php

namespace App\Observers\Bioestadistica;

use App\Application\Bioestadistica\Audit\AuditService;
use App\Models\Bioestadistica\Formulario;
use App\Models\Bioestadistica\Record;
use Illuminate\Database\Eloquent\Model;

class AuditObserver
{
    /** @var array<int, array<string, mixed>> */
    private static array $pendingOld = [];

    public function __construct(private AuditService $audit)
    {
    }

    public function created(Model $model): void
    {
        $this->audit->recordModel('create', $model, null, $model->getAttributes());
    }

    public function updating(Model $model): void
    {
        $old = [];
        foreach (array_keys($model->getDirty()) as $key) {
            $old[$key] = $model->getOriginal($key);
        }
        self::$pendingOld[spl_object_id($model)] = $old;
    }

    public function updated(Model $model): void
    {
        $id = spl_object_id($model);
        $old = self::$pendingOld[$id] ?? [];
        unset(self::$pendingOld[$id]);
        if ($old === []) {
            return;
        }

        $new = [];
        foreach (array_keys($old) as $key) {
            $new[$key] = $model->getAttribute($key);
        }

        $this->audit->recordModel($this->actionFor($model, $old, 'update'), $model, $old, $new);
    }

    public function deleted(Model $model): void
    {
        $this->audit->recordModel('delete', $model, $model->getOriginal() ?: $model->getAttributes(), null, [
            'phase' => method_exists($model, 'trashed') && $model->trashed() ? 'archive' : 'delete',
        ]);
    }

    /**
     * @param  array<string, mixed>  $changed
     */
    private function actionFor(Model $model, array $changed, string $default): string
    {
        if ($model instanceof Record && array_key_exists('estado', $changed)) {
            return match ($model->estado) {
                Record::ESTADO_ENVIADO => 'submit',
                Record::ESTADO_APROBADO => 'approve',
                Record::ESTADO_OBJETADO => 'reject',
                default => $default,
            };
        }

        if ($model instanceof Formulario
            && array_key_exists('estado', $changed)
            && $model->estado === 'activo'
        ) {
            return 'publish';
        }

        return $default;
    }
}
