<?php
// app/Notifications/TaskDeadlineAlert.php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Task;

class TaskDeadlineAlert extends Notification implements ShouldQueue
{
    use Queueable;

    public $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('【案件管理】タスク期限アラート')
            ->line('あなたに割り当てられたタスクの期限が近づいています。')
            ->line('案件名: ' . ($this->task->project->name ?? '-'))
            ->line('タスク名: ' . $this->task->title)
            ->line('期限: ' . ($this->task->due_date ? $this->task->due_date->format('Y-m-d') : '-'))
            ->action('タスクを確認', url('/tasks/' . $this->task->id));
    }

    public function toArray($notifiable)
    {
        return [
            'task_id' => $this->task->id,
            'title' => $this->task->title,
            'due_date' => $this->task->due_date,
            'project_name' => $this->task->project->name ?? '-',
        ];
    }
}
