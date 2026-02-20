<?php
namespace Tests\Unit;
use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class TaskTest extends TestCase
{
    use RefreshDatabase;
    public function test_task_belongs_to_project()
    {
        $project = Project::factory()->create();
        $task = Task::factory()->create(['project_id' => $project->id]);
        $this->assertInstanceOf(Project::class, $task->project);
        $this->assertEquals($project->id, $task->project->id);
    }
    public function test_task_has_assignments()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create();
        $assignment = $task->assignments()->create([
            'user_id' => $user->id
        ]);
        $this->assertTrue($task->assignments->contains($assignment));
    }
}