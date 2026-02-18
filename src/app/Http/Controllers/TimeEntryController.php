<?php

namespace App\Http\Controllers;

use App\Models\TimeEntry;
use App\Http\Requests\StoreTimeEntryRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TimeEntryController extends Controller
{
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
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $timeEntry = TimeEntry::create($data);
        return redirect()->route('time-entries.index')->with('success', '工数記録を登録しました');
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
        $timeEntry->update($request->validated());
        return redirect()->route('time-entries.index')->with('success', '工数記録を更新しました');
    }

    public function destroy(TimeEntry $timeEntry)
    {
        $this->authorize('delete', $timeEntry);
        $timeEntry->delete();
        return redirect()->route('time-entries.index')->with('success', '工数記録を削除しました');
    }
    public function storeDaily(Request $request)
    {
        $this->authorize('create', TimeEntry::class);
        $request->validate([
            'work_date' => ['required', 'date'],
            'tasks' => ['required', 'array'],
            'tasks.*.task_id' => ['required', 'exists:tasks,id'],
            'tasks.*.hours' => ['required', 'numeric', 'min:0.01', 'max:24'],
            'tasks.*.description' => ['nullable', 'string'],
        ]);
        $userId = \Auth::id();
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
    }
}
