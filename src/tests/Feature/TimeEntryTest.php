<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\TimeEntry;
use App\Models\User;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TimeEntryTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_time_entry_list()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        TimeEntry::factory()->count(3)->create(['user_id' => $user->id]);
        $response = $this->get(route('time-entries.index'));
        $response->assertStatus(200);
        $response->assertSee('工数記録一覧');
    }

    public function test_user_can_create_time_entry()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create();
        $this->actingAs($user);
        $data = [
            'task_id' => $task->id,
            'hours' => 2.5,
            'work_date' => now()->toDateString(),
            'description' => 'テスト作業',
        ];
        $response = $this->post(route('time-entries.store'), $data);
        $response->assertRedirect(route('time-entries.index'));
        $this->assertDatabaseHas('time_entries', [
            'task_id' => $task->id,
            'user_id' => $user->id,
            'hours' => 2.5,
        ]);
    }

    public function test_user_can_update_own_time_entry()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create();
        $entry = TimeEntry::factory()->create(['user_id' => $user->id, 'task_id' => $task->id]);
        $this->actingAs($user);
        $response = $this->put(route('time-entries.update', $entry), [
            'task_id' => $task->id,
            'hours' => 3.0,
            'work_date' => $entry->work_date,
            'description' => '更新',
        ]);
        $response->assertRedirect(route('time-entries.index'));
        $this->assertDatabaseHas('time_entries', [
            'id' => $entry->id,
            'hours' => 3.0,
        ]);
    }

    public function test_user_can_delete_own_time_entry()
    {
        $user = User::factory()->create();
        $entry = TimeEntry::factory()->create(['user_id' => $user->id]);
        $this->actingAs($user);
        $response = $this->delete(route('time-entries.destroy', $entry));
        $response->assertRedirect(route('time-entries.index'));
        $this->assertSoftDeleted('time_entries', ['id' => $entry->id]);
    }

    public function test_validation_fails_with_invalid_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $response = $this->post(route('time-entries.store'), [
            'task_id' => null,
            'hours' => 0,
            'work_date' => 'invalid-date',
        ]);
        $response->assertSessionHasErrors(['task_id', 'hours', 'work_date']);
    }
}
