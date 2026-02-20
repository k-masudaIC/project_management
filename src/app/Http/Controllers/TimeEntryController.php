<?php

namespace App\Http\Controllers;

use App\Models\TimeEntry;
use App\Http\Requests\StoreTimeEntryRequest;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class TimeEntryController extends Controller
{
    use AuthorizesRequests;
    public function index(Request $request)
    {
        $this->authorize('viewAny', TimeEntry::class);
        $query = TimeEntry::with(['task', 'user']);
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->has('task_id')) {
            $query->where('task_id', $request->task_id);
        }
        if ($request->has('work_date')) {
            $query->where('work_date', $request->work_date);
        }
        $timeEntries = $query->orderByDesc('work_date')->paginate(20);
        return view('time-entries.index', compact('timeEntries'));
    }

    public function create()
    {
        $this->authorize('create', TimeEntry::class);
        return view('time-entries.create');
    }

    public function store(StoreTimeEntryRequest $request)
    {
        $this->authorize('create', TimeEntry::class);
        try {
            $data = $request->validated();
            $data['user_id'] = Auth::id();
            $timeEntry = TimeEntry::create($data);
            return redirect()->route('time-entries.index')->with('success', '工数記録を登録しました');
        } catch (\Exception $e) {
            Log::error('TimeEntry create failed: ' . $e->getMessage(), ['exception' => $e]);
            return back()->withErrors(['error' => '工数記録の登録に失敗しました。']);
        }
    }

    public function show(TimeEntry $timeEntry)
    {
        $this->authorize('view', $timeEntry);
        return view('time-entries.show', compact('timeEntry'));
    }

    public function edit(TimeEntry $timeEntry)
    {
        $this->authorize('update', $timeEntry);
        return view('time-entries.edit', compact('timeEntry'));
    }

    public function update(StoreTimeEntryRequest $request, TimeEntry $timeEntry)
    {
        $this->authorize('update', $timeEntry);
        try {
            $timeEntry->update($request->validated());
            return redirect()->route('time-entries.index')->with('success', '工数記録を更新しました');
        } catch (\Exception $e) {
            Log::error('TimeEntry update failed: ' . $e->getMessage(), ['exception' => $e]);
            return back()->withErrors(['error' => '工数記録の更新に失敗しました。']);
        }
    }

    public function destroy(TimeEntry $timeEntry)
    {
        $this->authorize('delete', $timeEntry);
        try {
            $timeEntry->delete();
            return redirect()->route('time-entries.index')->with('success', '工数記録を削除しました');
        } catch (\Exception $e) {
            Log::error('TimeEntry delete failed: ' . $e->getMessage(), ['exception' => $e]);
            return back()->withErrors(['error' => '工数記録の削除に失敗しました。']);
        }
    }
    public function storeDaily(\App\Http\Requests\StoreDailyTimeEntryRequest $request)
    {
        $this->authorize('create', TimeEntry::class);
        try {
            $userId = Auth::id();
            foreach ($request->tasks as $task) {
                TimeEntry::updateOrCreate([
                    'task_id' => $task['task_id'],
                    'user_id' => $userId,
                    'work_date' => $request->work_date,
                ], [
                    'hours' => $task['hours'],
                    'description' => $task['description'] ?? null,
                ]);
            }
            return redirect()->route('time-entries.daily')->with('success', '日次工数を登録しました');
        } catch (\Exception $e) {
            Log::error('TimeEntry daily create failed: ' . $e->getMessage(), ['exception' => $e]);
            return back()->withErrors(['error' => '日次工数の登録に失敗しました。']);
        }
    }
    /**
     * 工数集計（ユーザー別・案件別・日別）
     */
    public function summary(Request $request)
    {
        $this->authorize('viewAny', TimeEntry::class);
        $query = \App\Models\TimeEntry::query();
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->has('project_id')) {
            $query->whereHas('task', function ($q) use ($request) {
                $q->where('project_id', $request->project_id);
            });
        }
        if ($request->has('from')) {
            $query->where('work_date', '>=', $request->from);
        }
        if ($request->has('to')) {
            $query->where('work_date', '<=', $request->to);
        }
        $summary = $query->with(['user', 'task.project'])
            ->selectRaw('user_id, SUM(hours) as total_hours')
            ->groupBy('user_id')
            ->get();
        return view('time-entries.summary', compact('summary'));
    }
}
