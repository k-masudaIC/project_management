<?php
namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Http\Requests\StoreTaskRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Log;

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
            return redirect()->route('tasks.index')->with('success', 'タスクを登録しました');
        } catch (\Exception $e) {
            Log::error('Task create failed: ' . $e->getMessage(), ['exception' => $e]);
            return back()->withErrors(['error' => 'タスクの作成に失敗しました。']);
        }
    }

    public function show(Task $task)
    {
        $task->load(['project', 'creator', 'assignments.user']);
        $users = \App\Models\User::where('is_active', true)->get();
        return view('tasks.show', compact('task', 'users'));
    }

    public function edit(Task $task)
    {
        $projects = Project::all();
        $users = \App\Models\User::where('is_active', true)->get();
        $task->load(['assignments.user']);
        return view('tasks.edit', compact('task', 'projects', 'users'));
    }

    public function update(StoreTaskRequest $request, Task $task)
    {
        try {
            DB::transaction(function () use ($request, $task) {
                $task->update($request->validated());

                // 担当者（assignees）更新処理
                $assignees = $request->input('assignees', []);
                // 既存の担当者を全削除
                $task->assignments()->delete();
                // 新しい担当者を登録
                foreach ($assignees as $userId) {
                    $task->assignments()->create(['user_id' => $userId]);
                }
            });
            return redirect()->route('tasks.index')->with('success', 'タスク情報を更新しました');
        } catch (\Exception $e) {
            Log::error('Task update failed: ' . $e->getMessage(), ['exception' => $e]);
            return back()->withErrors(['error' => 'タスクの更新に失敗しました。']);
        }
    }

    public function destroy(Task $task)
    {
        try {
            $task->delete();
            return redirect()->route('tasks.index')->with('success', 'タスクを削除しました');
        } catch (\Exception $e) {
            Log::error('Task delete failed: ' . $e->getMessage(), ['exception' => $e]);
            return back()->withErrors(['error' => 'タスクの削除に失敗しました。']);
        }
    }
}
