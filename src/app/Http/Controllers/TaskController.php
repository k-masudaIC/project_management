<?php
namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Http\Requests\StoreTaskRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TaskController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->authorizeResource(Task::class, 'task');
    }

    public function index(Request $request)
    {
        $query = Task::with(['project', 'creator']);
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }
        $tasks = $query->orderByDesc('created_at')->paginate(20);
        $projects = Project::all();
        return view('tasks.index', compact('tasks', 'projects'));
    }

    public function create()
    {
        $projects = Project::all();
        return view('tasks.create', compact('projects'));
    }

    public function store(StoreTaskRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $data = $request->validated();
                $data['created_by'] = auth()->id() ?? 1;
                Task::create($data);
            });
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'タスクの作成に失敗しました。']);
        }
        return redirect()->route('tasks.index')->with('success', 'タスクを登録しました');
    }

    public function show(Task $task)
    {
        $task->load(['project', 'creator']);
        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        $projects = Project::all();
        return view('tasks.edit', compact('task', 'projects'));
    }

    public function update(StoreTaskRequest $request, Task $task)
    {
        try {
            DB::transaction(function () use ($request, $task) {
                $task->update($request->validated());
            });
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'タスクの更新に失敗しました。']);
        }
        return redirect()->route('tasks.index')->with('success', 'タスク情報を更新しました');
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('tasks.index')->with('success', 'タスクを削除しました');
    }
}
