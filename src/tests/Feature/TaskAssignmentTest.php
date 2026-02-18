<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use App\Models\TaskAssignment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskAssignmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_pm_can_assign_user_to_task(): void
    {
        $pm = User::factory()->create(['role' => 'pm']);
        $user = User::factory()->create();
        $task = Task::factory()->create();

        $this->actingAs($pm)
            ->post(route('task-assignments.store'), [
                'task_id' => $task->id,
                'user_id' => $user->id,
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('task_assignments', [
            'task_id' => $task->id,
            'user_id' => $user->id,
        ]);
    }

    public function test_member_cannot_assign_user_to_task(): void
    {
        $member = User::factory()->create(['role' => 'member']);
        $user = User::factory()->create();
        $task = Task::factory()->create();

        $this->actingAs($member)
            ->post(route('task-assignments.store'), [
                'task_id' => $task->id,
                'user_id' => $user->id,
            ])
            ->assertForbidden();
    }

    public function test_pm_can_remove_assignment(): void
    {
        $pm = User::factory()->create(['role' => 'pm']);
        $user = User::factory()->create();
        $task = Task::factory()->create();
        $assignment = TaskAssignment::factory()->create([
            'task_id' => $task->id,
            'user_id' => $user->id,
        ]);

        $this->actingAs($pm)
            ->delete(route('task-assignments.destroy', $assignment))
            ->assertRedirect();

        $this->assertDatabaseMissing('task_assignments', [
            'id' => $assignment->id,
        ]);
    }
}
