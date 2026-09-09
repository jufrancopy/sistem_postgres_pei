<?php

use App\Application\Bioestadistica\Sp11Matrix;
use App\Models\Bioestadistica\Field;
use App\Models\Bioestadistica\Formulario;
use App\Models\Bioestadistica\Record;
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

        $recordIds = Record::withTrashed()
            ->where('formulario_id', $formulario->id)
            ->pluck('id');

        if ($recordIds->isNotEmpty()) {
            RecordValue::query()->whereIn('record_id', $recordIds)->delete();
            Record::withTrashed()->whereIn('id', $recordIds)->forceDelete();
        }

        Field::query()
            ->where('code', 'paciente_dia')
            ->whereHas('seccion', fn ($query) => $query->where('formulario_id', $formulario->id))
            ->update([
                'label' => 'Censo diario',
                'config' => Sp11Matrix::defaultConfig(),
            ]);

        $formulario->update([
            'descripcion' => 'Censo diario del mes: principio del día, ingresos, egresos desglosados y pacientes día.',
        ]);
    }

    public function down(): void
    {
        // Datos previos eliminados a propósito; no se restauran.
    }
};
