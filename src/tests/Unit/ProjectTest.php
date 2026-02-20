<?php
namespace Tests\Unit;
use App\Models\Project;
use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class ProjectTest extends TestCase
{
    use RefreshDatabase;
    public function test_project_belongs_to_client()
    {
        $client = Client::factory()->create();
        $project = Project::factory()->create(['client_id' => $client->id]);
        $this->assertInstanceOf(Client::class, $project->client);
        $this->assertEquals($client->id, $project->client->id);
    }
    public function test_project_has_many_tasks()
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();
        $task = $project->tasks()->create([
            'title' => 'テストタスク',
            'status' => 'not_started',
            'priority' => 'medium',
            'created_by' => $user->id
        ]);
        $this->assertTrue($project->tasks->contains($task));
    }
    public function test_calculate_total_hours()
    {
        $user = User::factory()->create();
        $project = Project::factory()->create();
        $project->tasks()->create([
            'title' => 'A', 'status' => 'not_started', 'priority' => 'medium', 'estimated_hours' => 5, 'created_by' => $user->id
        ]);
        $project->tasks()->create([
            'title' => 'B', 'status' => 'not_started', 'priority' => 'medium', 'estimated_hours' => 3, 'created_by' => $user->id
        ]);
        $total = $project->tasks()->sum('estimated_hours');
        $this->assertEquals(8, $total);
    }
}