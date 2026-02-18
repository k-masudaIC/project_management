<?php

namespace Database\Factories;

use App\Models\TaskAssignment;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskAssignmentFactory extends Factory
{
    protected $model = TaskAssignment::class;

    public function definition(): array
    {
        return [
            'task_id' => Task::factory(),
            'user_id' => User::factory(),
        ];
    }
}
