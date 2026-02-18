<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\User;
use App\Models\TaskAssignment;

class TaskAssignmentSeeder extends Seeder
{
    public function run(): void
    {
        $tasks = Task::all();
        $users = User::all();
        foreach ($tasks as $task) {
            // 各タスクに2人アサイン
            $assignUsers = $users->random(min(2, $users->count()));
            foreach ($assignUsers as $user) {
                TaskAssignment::firstOrCreate([
                    'task_id' => $task->id,
                    'user_id' => $user->id,
                ]);
            }
        }
    }
}
