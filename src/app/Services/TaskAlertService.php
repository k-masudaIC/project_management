<?php
// app/Services/TaskAlertService.php
namespace App\Services;

use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class TaskAlertService
{
    /**
     * 期限が迫っているタスクを取得
     * @param int $days
     * @return \Illuminate\Support\Collection
     */
    public function getUpcomingTasks(int $days = 3)
    {
        $user = Auth::user();
        $today = now();
        $limit = now()->addDays($days);
        return Task::with('project')
            ->whereHas('assignments', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->where('due_date', '>=', $today)
            ->where('due_date', '<=', $limit)
            ->orderBy('due_date')
            ->get();
    }
}
