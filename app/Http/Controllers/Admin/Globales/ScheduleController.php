<?php

namespace App\Http\Controllers\Admin\Globales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Period;
use App\Models\ScheduleItem;
use App\Services\CronogramaImporter;

class ScheduleController extends Controller
{
    public function index()
    {
        $periods = Period::withCount('schedules')->orderByDesc('id')->get();
        return view('admin.globales.cronogramas.index', compact('periods'));
    }

    public function show(Period $period)
    {
        $period->load(['schedules.items.responsible']);
        return view('admin.globales.cronogramas.show', compact('period'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:json,txt',
        ]);

        $file = $request->file('file');
        $json = json_decode(file_get_contents($file->getRealPath()), true);

        if (!$json) {
            return back()->withErrors(['file' => 'El archivo JSON no es válido.']);
        }

        $importer = new CronogramaImporter();
        $period = $importer->import($json);

        return redirect()->route('globales.cronogramas.show', $period->id)
            ->with('success', 'Cronograma importado correctamente.');
    }

    public function gantt(Request $request)
    {
        $periodId = $request->get('period_id');
        $department = $request->get('department');

        $query = ScheduleItem::query()->with('responsible', 'schedule');
        if ($periodId) {
            $query->whereHas('schedule', function ($q) use ($periodId) {
                $q->where('period_id', $periodId);
            });
        }
        if ($department) {
            $query->whereHas('schedule', function ($q) use ($department) {
                $q->where('department', $department);
            });
        }

        $items = $query->get();

        $data = $items->map(function ($it) {
            return [
                'id' => $it->id,
                'text' => $it->title ?? 'Item',
                'start_date' => $it->start_date?->format('Y-m-d'),
                'end_date' => $it->end_date?->format('Y-m-d'),
                'responsible' => $it->responsible?->name,
                'status' => $it->status,
                'activity_task_id' => $it->activity_task_id,
            ];
        });

        return response()->json(['data' => $data]);
    }
}
