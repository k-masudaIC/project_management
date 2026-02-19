<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Project;
use App\Models\Task;
use App\Models\TimeEntry;
use App\Models\User;
use App\Services\DashboardService;
use App\Services\TaskAlertService;

class DashboardController extends Controller
{
    public function index(DashboardService $dashboardService, TaskAlertService $taskAlertService)
    {
        $user = Auth::user();

        // 担当タスク一覧
        $tasks = Task::with(['project', 'assignments.user'])
            ->whereHas('assignments', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->orderBy('due_date')
            ->limit(5)
            ->get();

        // 今週の工数
        $startOfWeek = now()->startOfWeek();
        $endOfWeek = now()->endOfWeek();
        $weeklyHours = TimeEntry::where('user_id', $user->id)
            ->whereBetween('work_date', [$startOfWeek, $endOfWeek])
            ->sum('hours');

        // 期限が迫っているタスク
        $upcomingTasks = Task::with('project')
            ->whereHas('assignments', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->where('due_date', '>=', now())
            ->orderBy('due_date')
            ->limit(5)
            ->get();

        // プロジェクト進捗率（例: 完了タスク/全タスク）
        $projects = Project::with(['tasks' => function($q) { $q->select('id', 'project_id', 'status'); }])
            ->select('id', 'name', 'code', 'status', 'end_date')
            ->get();
        $projectProgress = $projects->map(function($project) {
            $total = $project->tasks->count();
            $completed = $project->tasks->where('status', 'completed')->count();
            $progress = $total > 0 ? round($completed / $total * 100, 1) : 0;
            return [
                'id' => $project->id,
                'name' => $project->name,
                'code' => $project->code,
                'status' => $project->status,
                'end_date' => $project->end_date,
                'progress' => $progress,
            ];
        });

        // メンバー別稼働グラフ用データ
        $memberWorkload = $dashboardService->getMemberWorkloadSummary();

        // 期限アラート（3日以内）
        $alertTasks = $taskAlertService->getUpcomingTasks(3);

        return view('dashboard', compact('tasks', 'weeklyHours', 'upcomingTasks', 'projectProgress', 'memberWorkload', 'alertTasks'));
    }
}
