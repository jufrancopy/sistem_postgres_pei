<?php

namespace App\Services;

use App\Models\Period;
use App\Models\Schedule;
use App\Models\ScheduleItem;
use App\Models\User;

class CronogramaImporter
{
    public function import(array $json): Period
    {
        $period = Period::create([
            'name' => $json['periodo'] ?? ($json['institucion'] ?? 'Cronograma') . ' - ' . now()->year,
            'description' => $json['descripcion'] ?? null,
        ]);

        foreach ($json['departamentos'] ?? [] as $dept) {
            $schedule = Schedule::create([
                'period_id' => $period->id,
                'department' => $dept['nombre'] ?? $dept['nombre_departamento'] ?? 'Sin departamento',
                'title' => $dept['nombre'] ?? $dept['nombre_departamento'] ?? 'Sin título',
                'description' => $dept['descripcion'] ?? null,
            ]);

            $responsibleId = $this->findResponsible($dept);

            foreach ($dept['actividades_operativas'] ?? [] as $act) {
                $item = ScheduleItem::create([
                    'schedule_id' => $schedule->id,
                    'title' => $act['descripcion'] ?? $act['nombre'] ?? 'Actividad operativa',
                    'description' => json_encode($act),
                    'start_date' => $this->normalizeDate($act['inicio'] ?? $act['start'] ?? $act['mes'] ?? null),
                    'end_date' => $this->normalizeDate($act['fin'] ?? $act['end'] ?? null),
                    'responsible_id' => $this->findResponsible($act) ?? $responsibleId,
                    'status' => $act['estado'] ?? null,
                    'meta' => $act,
                ]);

                if (isset($act['cronograma']) && is_array($act['cronograma'])) {
                    foreach ($act['cronograma'] as $subItem) {
                        ScheduleItem::create([
                            'schedule_id' => $schedule->id,
                            'title' => ($act['categoria'] ?? 'Cronograma') . ' - ' . ($subItem['destino'] ?? $subItem['actividad'] ?? 'Detalle'),
                            'description' => json_encode($subItem),
                            'start_date' => $this->normalizeDate($subItem['inicio'] ?? $subItem['start'] ?? null),
                            'end_date' => $this->normalizeDate($subItem['fin'] ?? $subItem['end'] ?? null),
                            'responsible_id' => $this->findResponsible($subItem) ?? $responsibleId,
                            'status' => $subItem['estado'] ?? null,
                            'meta' => $subItem,
                        ]);
                    }
                }
            }
        }

        return $period;
    }

    protected function findResponsible(array $data): ?int
    {
        $names = [];

        if (!empty($data['responsables']) && is_array($data['responsables'])) {
            $names = $data['responsables'];
        }

        if (!empty($data['responsable'])) {
            $names[] = $data['responsable'];
        }

        foreach ($names as $name) {
            if (!is_string($name) || trim($name) === '') {
                continue;
            }
            $user = User::where('name', 'like', "%{$name}%")->first();
            if ($user) {
                return $user->id;
            }
        }

        return null;
    }

    protected function normalizeDate($value): ?string
    {
        if (!$value) {
            return null;
        }

        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
            return $value;
        }

        if (preg_match('/^(\w+)\s*(\d{4})$/', $value, $m)) {
            try {
                return date('Y-m-d', strtotime($m[1] . ' 1 ' . $m[2]));
            } catch (\Exception $e) {
                return null;
            }
        }

        try {
            $date = date_create($value);
            return $date ? $date->format('Y-m-d') : null;
        } catch (\Exception $e) {
            return null;
        }
    }
}
