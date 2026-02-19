<?php
// app/Console/Commands/SendTaskDeadlineAlerts.php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;
use App\Notifications\TaskDeadlineAlert;
use Illuminate\Support\Facades\Notification;
use Carbon\Carbon;

class SendTaskDeadlineAlerts extends Command
{
    protected $signature = 'tasks:send-deadline-alerts';
    protected $description = '期限が迫っているタスクの担当者に通知を送信';

    public function handle()
    {
        $today = Carbon::today();
        $limit = Carbon::today()->addDays(3);
        $tasks = Task::with(['assignments.user', 'project'])
            ->where('due_date', '>=', $today)
            ->where('due_date', '<=', $limit)
            ->get();

        foreach ($tasks as $task) {
            foreach ($task->assignments as $assignment) {
                if ($assignment->user) {
                    $assignment->user->notify(new TaskDeadlineAlert($task));
                }
            }
        }
        $this->info('期限アラート通知を送信しました。');
    }
}
