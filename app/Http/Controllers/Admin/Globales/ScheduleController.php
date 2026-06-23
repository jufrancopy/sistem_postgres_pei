<?php

namespace App\Http\Controllers\Admin\Globales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Period;
use App\Models\Schedule;
use App\Models\ScheduleItem;

class ScheduleController extends Controller
{
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
