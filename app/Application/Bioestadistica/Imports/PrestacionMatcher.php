<?php

namespace App\Application\Bioestadistica\Imports;

use App\Models\Bioestadistica\DetalleCatalogoItem;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class PrestacionMatcher
{
    public const THRESHOLD = 0.75;

    /** @var Collection<int, array{id:int,catalogo_id:int,label:string,key:string,domain:?string}>|null */
    private ?Collection $cache = null;

    /**
     * @return array{nivel: int, catalog_item_id: int|null, catalogo_id: int|null, sugerencia: string|null, score: float|null}
     */
    public function match(string $label, ?string $domainCode = null): array
    {
        $normalized = $this->normalize($label);
        if ($normalized === '') {
            return ['nivel' => 4, 'catalog_item_id' => null, 'catalogo_id' => null, 'sugerencia' => null, 'score' => null];
        }

        $items = $this->items();
        if ($domainCode) {
            $inDomain = $items->first(
                fn (array $item) => $item['domain'] === (string) $domainCode && $item['key'] === $normalized
            );
            if ($inDomain) {
                return $this->hit(2, $inDomain, 1.0);
            }
        }

        $exact = $items->first(fn (array $item) => $item['key'] === $normalized);
        if ($exact) {
            return $this->hit(1, $exact, 1.0);
        }

        $best = null;
        $bestScore = 0.0;
        $pool = $domainCode
            ? $items->filter(fn (array $item) => $item['domain'] === (string) $domainCode)
            : $items;
        if ($pool->isEmpty()) {
            $pool = $items;
        }
        foreach ($pool as $item) {
            $score = $this->trigram($normalized, $item['key']);
            if ($score > $bestScore) {
                $bestScore = $score;
                $best = $item;
            }
        }

        if ($best && $bestScore >= self::THRESHOLD) {
            return $this->hit(3, $best, $bestScore);
        }

        return [
            'nivel' => 4,
            'catalog_item_id' => null,
            'catalogo_id' => $best['catalogo_id'] ?? null,
            'sugerencia' => $best['label'] ?? null,
            'score' => $best ? round($bestScore, 3) : null,
        ];
    }

    public function normalize(string $value): string
    {
        $value = preg_replace('/\(\s*\d+\s*\)/', ' ', $value) ?? $value;
        $value = Str::upper(Str::ascii($value));

        return trim((string) preg_replace('/[^A-Z0-9]+/', ' ', $value));
    }

    public function resolveManual(int $catalogItemId, ?string $domainCode = null): ?DetalleCatalogoItem
    {
        return DetalleCatalogoItem::query()
            ->where('catalogo_item_id', $catalogItemId)
            ->where('activo', true)
            ->when($domainCode, function ($query) use ($domainCode) {
                $query->whereHas('detalle.variable', fn ($variable) => $variable->where('codigo', $domainCode));
            })
            ->with('detalle')
            ->first();
    }

    /**
     * @return Collection<int, array{id:int,catalogo_id:int,label:string,key:string,domain:?string}>
     */
    private function items(): Collection
    {
        if ($this->cache !== null) {
            return $this->cache;
        }

        return $this->cache = DetalleCatalogoItem::query()
            ->where('activo', true)
            ->with('detalle.variable')
            ->orderBy('orden')
            ->get()
            ->map(function (DetalleCatalogoItem $bridge) {
                $item = $bridge->resolveCatalogItem();

                return [
                    'id' => (int) $bridge->catalogo_item_id,
                    'catalogo_id' => (int) $bridge->variable_detalle_id,
                    'label' => $item?->nombre ?? ('Ítem #'.$bridge->catalogo_item_id),
                    'key' => $this->normalize($item?->nombre ?? ''),
                    'domain' => $bridge->detalle?->variable?->codigo !== null
                        ? (string) $bridge->detalle->variable->codigo
                        : null,
                ];
            })
            ->filter(fn (array $item) => $item['key'] !== '')
            ->values();
    }

    /**
     * @param  array{id:int,catalogo_id:int,label:string}  $item
     * @return array{nivel:int,catalog_item_id:int,catalogo_id:int,sugerencia:string,score:float}
     */
    private function hit(int $level, array $item, float $score): array
    {
        return [
            'nivel' => $level,
            'catalog_item_id' => $item['id'],
            'catalogo_id' => $item['catalogo_id'],
            'sugerencia' => $item['label'],
            'score' => round($score, 3),
        ];
    }

    private function trigram(string $left, string $right): float
    {
        if ($left === $right) {
            return 1.0;
        }
        $a = $this->grams($left);
        $b = $this->grams($right);
        if ($a === [] || $b === []) {
            return 0.0;
        }
        $intersect = count(array_intersect($a, $b));

        return (2 * $intersect) / (count($a) + count($b));
    }

    /**
     * @return array<int, string>
     */
    private function grams(string $value): array
    {
        $padded = '  '.$value.' ';
        $grams = [];
        $length = mb_strlen($padded);
        for ($i = 0; $i < $length - 2; $i++) {
            $grams[] = mb_substr($padded, $i, 3);
        }

        return array_values(array_unique($grams));
    }
}
