<?php

namespace Tests\Unit;

use App\Models\TimeEntry;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TimeEntryTest extends TestCase
{
    use RefreshDatabase;

    public function test_time_entry_belongs_to_task()
    {
        $task = Task::factory()->create();
        $entry = TimeEntry::factory()->create(['task_id' => $task->id]);
        $this->assertInstanceOf(Task::class, $entry->task);
    }

    public function test_time_entry_belongs_to_user()
    {
        $user = User::factory()->create();
        $entry = TimeEntry::factory()->create(['user_id' => $user->id]);
        $this->assertInstanceOf(User::class, $entry->user);
    }
}
