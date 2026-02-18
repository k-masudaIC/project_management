<?php

namespace Database\Factories;

use App\Models\TimeEntry;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TimeEntryFactory extends Factory
{
    protected $model = TimeEntry::class;

    public function definition(): array
    {
        return [
            'task_id' => Task::factory(),
            'user_id' => User::factory(),
            'hours' => $this->faker->randomFloat(2, 0.25, 8),
            'work_date' => $this->faker->date(),
            'description' => $this->faker->sentence(),
            'started_at' => null,
            'ended_at' => null,
        ];
    }
}
