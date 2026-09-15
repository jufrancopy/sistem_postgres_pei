<?php

namespace Database\Seeders;

use App\Application\Bioestadistica\Dictionary\DictionaryCodes;
use App\Application\Bioestadistica\Forms\TablaIpsConvenioColumns;
use App\Models\Bioestadistica\Formulario;
use App\Models\Bioestadistica\VariableDetalle;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

/**
 * Publica SP13 alineado a la planilla Formularios (7 programas).
 * VIH/TB/epidemiología → SP12. Diabetes/Nutrición quedan en diccionario pero fuera de SP13.
 */
class BioestadisticaSp13Seeder extends Seeder
{
    private const EXCLUDED_SLUGS = [
        'VAR_17_VIH',
        'VAR_17_TUBERCULOSIS_Y_EPOC',
        'VAR_17_EPIDEMIOLOGIA_Y_VIGILANCIA',
        'VAR_17_DIABETES_E_HIPERTENSION',
        'VAR_17_NUTRICION',
        'VAR_17_PROGRAMA_DE_VIH_SIDA',
        'VAR_17_PREVENCION_DE_LA_TRANSMISION_VERTICAL',
        'VAR_17_PROGRAMA_DE_TUBERCULOSIS',
        'VAR_17_ENFERMEDADES_NO_TRANSMISIBLES',
    ];

    private const DETALLE_ORDER = [
        'VAR_17_PROGRAMA_SALUD_SEXUAL_Y_REPRODUCTIVA',
        'VAR_17_PREVENCION_DEL_CANCER_CERVICO_UTERINO_Y_MAMAS',
        'VAR_17_VIOLENCIA',
        'VAR_17_ADULTOS_MAYORES_VIDA_PLENA',
        'VAR_17_SALUD_DEL_ADOLESCENTE',
        'VAR_17_SALUD_DEL_NINO',
        'VAR_17_SALUD_BUCODENTAL',
    ];

    public function run(): void
    {
        $formulario = Formulario::where('codigo', 'SP13')->first();
        if (! $formulario) {
            $this->command?->warn('SP13 no existe; ejecute BioestadisticaFormulariosSeeder primero.');

            return;
        }

        $detalles = $this->detallesSp13();
        if ($detalles->isEmpty()) {
            $this->command?->warn('Dominio 17 sin programas para SP13; ejecute BioestadisticaSp13ProgramasDictionarySeeder primero.');

            return;
        }

        $formulario->update([
            'descripcion' => 'Programas de salud según planilla SP13: SSR, cáncer, violencia, adultos mayores, adolescente, niño y bucodental.',
            'estado' => 'activo',
        ]);

        $seccion = $formulario->secciones()->updateOrCreate(
            ['titulo' => 'Programas de salud'],
            [
                'descripcion' => 'Siete programas de la planilla Formularios SP13.',
                'orden' => 1,
            ]
        );

        $orden = 1;
        $published = [];
        foreach ($detalles as $index => $detalle) {
            $code = DictionaryCodes::fieldCode($detalle);
            $this->upsertTable($seccion, $code, $detalle, $orden++, $index === 0);
            $published[] = $detalle->nombre.' ('.$detalle->catalogo_items_count.')';
        }

        $keepCodes = $detalles->map(fn (VariableDetalle $d) => DictionaryCodes::fieldCode($d))->all();
        $seccion->fields()
            ->where('type', 'tabla')
            ->whereNotIn('code', $keepCodes)
            ->each(fn ($field) => $field->delete());

        $observaciones = $formulario->secciones()->updateOrCreate(
            ['titulo' => 'Observaciones'],
            [
                'descripcion' => 'Comentarios del establecimiento sobre el período informado.',
                'orden' => 2,
            ]
        );

        $obsField = $observaciones->fields()->withTrashed()->firstOrNew(['code' => 'observaciones']);
        if ($obsField->trashed()) {
            $obsField->restore();
        }
        $obsField->fill([
            'label' => 'Observaciones de la planilla',
            'type' => 'textarea',
            'required' => false,
            'orden' => 1,
        ])->save();

        $this->command?->info('SP13 publicado con '.$detalles->count().' tablas de programas de salud.');
        foreach ($published as $line) {
            $this->command?->line('  - '.$line);
        }
    }

    /** @return Collection<int, VariableDetalle> */
    private function detallesSp13(): Collection
    {
        $allowed = self::DETALLE_ORDER;

        $detalles = VariableDetalle::query()
            ->where('activo', true)
            ->whereHas('variable', fn ($query) => $query->where('codigo', '17')->where('activo', true))
            ->with('variable')
            ->withCount(['catalogoItems as catalogo_items_count' => fn ($query) => $query->where('activo', true)])
            ->get()
            ->filter(function (VariableDetalle $detalle) use ($allowed) {
                $slug = DictionaryCodes::slug($detalle);
                if (in_array($slug, self::EXCLUDED_SLUGS, true)) {
                    return false;
                }
                if ($detalle->catalogo_items_count <= 0) {
                    return false;
                }

                return in_array($slug, $allowed, true);
            })
            ->values();

        $ordered = collect();
        foreach ($allowed as $slug) {
            $hit = $detalles->first(fn (VariableDetalle $d) => DictionaryCodes::slug($d) === $slug);
            if ($hit) {
                $ordered->push($hit);
            }
        }

        return $ordered->values();
    }

    private function upsertTable($seccion, string $code, VariableDetalle $detalle, int $orden, bool $required): void
    {
        $field = $seccion->fields()->withTrashed()->firstOrNew(['code' => $code]);
        if ($field->trashed()) {
            $field->restore();
        }

        $field->fill([
            'label' => $detalle->nombre,
            'type' => 'tabla',
            'required' => $required,
            'detalle_id' => $detalle->id,
            'help_text' => TablaIpsConvenioColumns::helpText(),
            'config' => TablaIpsConvenioColumns::config((int) $detalle->id, 'Prestación'),
            'orden' => $orden,
        ])->save();
    }
}
