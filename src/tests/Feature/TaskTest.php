<?php
namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_view_task_list()
    {
        $project = Project::factory()->create();
        Task::factory()->count(3)->create(['project_id' => $project->id]);
        $response = $this->get('/tasks');
        $response->assertStatus(200)->assertSee($project->name);
    }

    public function test_guest_can_view_task_detail()
    {
        $project = Project::factory()->create();
        $task = Task::factory()->create(['project_id' => $project->id]);
        $response = $this->get('/tasks/' . $task->id);
        $response->assertStatus(200)->assertSee($task->title);
    }

    public function test_auth_can_create_task()
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();
        $this->actingAs($user);
        $response = $this->post('/tasks', [
            'project_id' => $project->id,
            'title' => 'New Task',
            'status' => 'not_started',
            'priority' => 'medium',
        ]);
        $response->assertRedirect('/tasks');
        $this->assertDatabaseHas('tasks', ['title' => 'New Task']);
    }

    public function test_auth_can_edit_task()
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();
        $task = Task::factory()->create(['project_id' => $project->id]);
        $this->actingAs($user);
        $response = $this->put('/tasks/' . $task->id, [
            'project_id' => $project->id,
            'title' => 'Updated Task',
            'status' => 'completed',
            'priority' => 'high',
        ]);
        $response->assertRedirect('/tasks');
        $this->assertDatabaseHas('tasks', ['title' => 'Updated Task', 'status' => 'completed']);
    }

    public function test_auth_can_delete_task()
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();
        $task = Task::factory()->create(['project_id' => $project->id]);
        $this->actingAs($user);
        $response = $this->delete('/tasks/' . $task->id);
        $response->assertRedirect('/tasks');
        $this->assertSoftDeleted('tasks', ['id' => $task->id]);
    }
}
