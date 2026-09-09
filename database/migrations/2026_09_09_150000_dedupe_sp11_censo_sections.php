<?php

use App\Application\Bioestadistica\Sp11Matrix;
use App\Models\Bioestadistica\Field;
use App\Models\Bioestadistica\FormSeccion;
use App\Models\Bioestadistica\Formulario;
use App\Models\Bioestadistica\RecordValue;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $formulario = Formulario::query()->where('codigo', 'SP11')->first();
        if (! $formulario) {
            return;
        }

        $keepSection = FormSeccion::query()
            ->where('formulario_id', $formulario->id)
            ->orderBy('orden')
            ->orderBy('id')
            ->first();
        if (! $keepSection) {
            return;
        }

        $keepSection->update([
            'titulo' => 'Censo diario',
            'descripcion' => 'Principio del día, ingresos, egresos (altas/traslados/óbitos/abandono) y totales calculados.',
            'orden' => 1,
        ]);

        $keepField = Field::withTrashed()
            ->where('seccion_id', $keepSection->id)
            ->where('code', 'paciente_dia')
            ->orderBy('id')
            ->first();

        if (! $keepField) {
            $keepField = Field::create([
                'seccion_id' => $keepSection->id,
                'code' => 'paciente_dia',
                'label' => 'Matriz mensual',
                'type' => 'matriz',
                'required' => true,
                'config' => Sp11Matrix::defaultConfig(),
                'orden' => 1,
                'help_text' => 'Total egresos y total pacientes día se calculan automáticamente.',
            ]);
        } else {
            if ($keepField->trashed()) {
                $keepField->restore();
            }
            $keepField->fill([
                'label' => 'Matriz mensual',
                'type' => 'matriz',
                'required' => true,
                'config' => Sp11Matrix::defaultConfig(),
                'orden' => 1,
                'help_text' => 'Total egresos y total pacientes día se calculan automáticamente.',
            ])->save();
        }

        $duplicateFields = Field::withTrashed()
            ->where('code', 'paciente_dia')
            ->where('id', '<>', $keepField->id)
            ->whereHas('seccion', fn ($q) => $q->where('formulario_id', $formulario->id))
            ->get();

        foreach ($duplicateFields as $duplicate) {
            RecordValue::query()
                ->where('field_id', $duplicate->id)
                ->orderBy('id')
                ->each(function (RecordValue $value) use ($keepField) {
                    $exists = RecordValue::query()
                        ->where('record_id', $value->record_id)
                        ->where('field_id', $keepField->id)
                        ->exists();
                    if ($exists) {
                        $value->delete();

                        return;
                    }
                    $value->forceFill(['field_id' => $keepField->id])->save();
                });
            $duplicate->delete();
        }

        FormSeccion::query()
            ->where('formulario_id', $formulario->id)
            ->where('id', '<>', $keepSection->id)
            ->get()
            ->each(function (FormSeccion $section) {
                $section->fields()->withTrashed()->get()->each->delete();
                $section->delete();
            });
    }

    public function down(): void
    {
        // Irreversible consolidation.
    }
};
