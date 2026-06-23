<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\Period;
use App\Models\Schedule;
use App\Models\ScheduleItem;
use App\Models\User;

class ImportCronograma extends Command
{
    protected $signature = 'import:cronograma {file}';

    protected $description = 'Importa un JSON de cronograma y crea periodo, schedules y items';

    public function handle()
    {
        $file = $this->argument('file');
        if (!file_exists($file)) {
            $this->error('File not found: ' . $file);
            return 1;
        }

        $json = json_decode(file_get_contents($file), true);
        if (!$json) {
            $this->error('Invalid JSON');
            return 1;
        }

        $period = Period::create([
            'name' => $json['periodo'] ?? ($json['institucion'] . ' - ' . now()->year),
        ]);

        foreach ($json['departamentos'] ?? [] as $dept) {
            $schedule = Schedule::create([
                'period_id' => $period->id,
                'department' => $dept['nombre'] ?? $dept['nombre_departamento'] ?? 'Sin nombre',
                'title' => $dept['nombre'] ?? null,
            ]);

            // actividades_operativas
            foreach ($dept['actividades_operativas'] ?? [] as $act) {
                // build item
                $title = $act['descripcion'] ?? $act['nombre'] ?? 'Tarea';
                $start = $act['inicio'] ?? $act['start'] ?? $act['mes'] ?? null;
                $end = $act['fin'] ?? $act['end'] ?? null;

                // map responsable
                $responsibleId = null;
                $names = $dept['responsables'] ?? [];
                if ($names && is_array($names)) {
                    // pick first responsable for task
                    $name = $names[0];
                    $user = User::where('name', 'like', "%{$name}%")->first();
                    if ($user) $responsibleId = $user->id;
                }

                $item = ScheduleItem::create([
                    'schedule_id' => $schedule->id,
                    'title' => $title,
                    'description' => json_encode($act),
                    'start_date' => $this->normalizeDate($start),
                    'end_date' => $this->normalizeDate($end),
                    'responsible_id' => $responsibleId,
                    'status' => $act['estado'] ?? null,
                    'meta' => $act,
                ]);
            }

            // cronograma arrays (ej. viajes)
            foreach ($dept['actividades_operativas'] ?? [] as $group) {
                if (isset($group['cronograma']) && is_array($group['cronograma'])) {
                    foreach ($group['cronograma'] as $trip) {
                        ScheduleItem::create([
                            'schedule_id' => $schedule->id,
                            'title' => ($group['categoria'] ?? 'Cronograma') . ' - ' . ($trip['destino'] ?? 'Destino'),
                            'description' => json_encode($trip),
                            'start_date' => $this->normalizeDate($trip['inicio'] ?? null),
                            'end_date' => $this->normalizeDate($trip['fin'] ?? null),
                            'meta' => $trip,
                        ]);
                    }
                }
            }
        }

        $this->info('Import completed. Period id: ' . $period->id);
        return 0;
    }

    protected function normalizeDate($value)
    {
        if (!$value) return null;
        // try YYYY-MM-DD
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) return $value;
        // try formats like "Julio 2026" -> set to first day of month
        if (preg_match('/^(\w+)\s*(\d{4})$/', $value, $m)) {
            try {
                return date('Y-m-d', strtotime($m[1] . ' 1 ' . $m[2]));
            } catch (\Exception $e) {
                return null;
            }
        }
        return null;
    }
}
