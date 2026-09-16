<?php

namespace App\Application\Bioestadistica\Capture;

use App\Application\Bioestadistica\Dictionary\CatalogItemResolver;
use App\Models\Bioestadistica\Formulario;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

/**
 * Índice para localizar en qué SP aparece un campo / ítem de diccionario.
 */
class VariableLocatorService
{
    public function __construct(
        private CatalogItemResolver $resolver
    ) {}

    /**
     * @return list<array{sp: string, formulario: string, seccion: string, campo: string, item: ?string, search: string}>
     */
    public function index(): array
    {
        $version = (string) Formulario::query()->max('updated_at');

        return Cache::remember('bio.variable_locator.'.$version, 3600, function () {
            $rows = [];
            $forms = Formulario::query()
                ->where('estado', 'activo')
                ->with(['secciones.fields.detalle'])
                ->ordenSp()
                ->get();

            foreach ($forms as $form) {
                $sp = (string) $form->codigo;
                $formName = (string) $form->nombre;
                $rows[] = [
                    'sp' => $sp,
                    'formulario' => $formName,
                    'seccion' => '',
                    'campo' => '',
                    'item' => null,
                    'search' => mb_strtolower($sp.' '.$formName),
                ];

                foreach ($form->secciones as $seccion) {
                    $secTitle = (string) $seccion->titulo;
                    if ($secTitle !== '') {
                        $rows[] = [
                            'sp' => $sp,
                            'formulario' => $formName,
                            'seccion' => $secTitle,
                            'campo' => '',
                            'item' => null,
                            'search' => mb_strtolower($sp.' '.$formName.' '.$secTitle),
                        ];
                    }

                    foreach ($seccion->fields as $field) {
                        $fieldLabel = (string) $field->label;
                        $rows[] = [
                            'sp' => $sp,
                            'formulario' => $formName,
                            'seccion' => $secTitle,
                            'campo' => $fieldLabel,
                            'item' => null,
                            'search' => mb_strtolower($sp.' '.$formName.' '.$secTitle.' '.$fieldLabel.' '.$field->code),
                        ];

                        if (! in_array($field->type, ['tabla', 'subtabla'], true)) {
                            continue;
                        }

                        foreach ($this->resolver->rowsForDetalle($field->detalle) as $item) {
                            $itemLabel = (string) $item->label;
                            $rows[] = [
                                'sp' => $sp,
                                'formulario' => $formName,
                                'seccion' => $secTitle,
                                'campo' => $fieldLabel,
                                'item' => $itemLabel,
                                'search' => mb_strtolower($sp.' '.$formName.' '.$secTitle.' '.$fieldLabel.' '.$itemLabel),
                            ];
                        }
                    }
                }
            }

            return $rows;
        });
    }

    /**
     * @return Collection<int, array{sp: string, formulario: string, seccion: string, campo: string, item: ?string, label: string}>
     */
    public function search(string $query, int $limit = 40): Collection
    {
        $q = mb_strtolower(trim($query));
        if (mb_strlen($q) < 2) {
            return collect();
        }

        $tokens = preg_split('/\s+/u', $q) ?: [];

        return collect($this->index())
            ->filter(function (array $row) use ($tokens) {
                foreach ($tokens as $token) {
                    if ($token === '') {
                        continue;
                    }
                    if (! str_contains($row['search'], $token)) {
                        return false;
                    }
                }

                return true;
            })
            ->map(function (array $row) {
                $parts = array_filter([
                    $row['campo'] !== '' ? $row['campo'] : null,
                    $row['item'],
                    $row['seccion'] !== '' && $row['campo'] === '' ? $row['seccion'] : null,
                    $row['campo'] === '' && $row['seccion'] === '' ? $row['formulario'] : null,
                ]);

                return [
                    'sp' => $row['sp'],
                    'formulario' => $row['formulario'],
                    'seccion' => $row['seccion'],
                    'campo' => $row['campo'],
                    'item' => $row['item'],
                    'label' => implode(' › ', $parts) ?: $row['formulario'],
                ];
            })
            ->unique(fn (array $row) => $row['sp'].'|'.$row['campo'].'|'.($row['item'] ?? ''))
            ->take($limit)
            ->values();
    }
}
