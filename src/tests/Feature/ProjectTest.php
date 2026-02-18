<?php
namespace Tests\Feature;

use App\Models\Project;
use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_project_list()
    {
        $user = User::factory()->create();
        $client = Client::factory()->create();
        Project::factory()->count(3)->create(['client_id' => $client->id]);
        $response = $this->actingAs($user)->get('/projects');
        $response->assertStatus(200)->assertSee('案件一覧');
    }

    public function test_user_can_create_project()
    {
        $client = Client::factory()->create();
        $user = User::factory()->create(['role' => 'admin']);
        $data = [
            'client_id' => $client->id,
            'name' => 'テスト案件',
            'code' => 'PRJ-2026-001',
            'status' => 'proposal',
        ];
        $response = $this->actingAs($user)->post('/projects', $data);
        $response->assertRedirect('/projects');
        $this->assertDatabaseHas('projects', ['name' => 'テスト案件']);
    }

    public function test_user_cannot_create_project_without_permission()
    {
        $client = Client::factory()->create();
        $user = User::factory()->create(['role' => 'member']);
        $data = [
            'client_id' => $client->id,
            'name' => '権限なし案件',
            'code' => 'PRJ-2026-002',
            'status' => 'proposal',
        ];
        $response = $this->actingAs($user)->post('/projects', $data);
        $response->assertForbidden();
    }

    public function test_user_can_update_own_project()
    {
        $client = Client::factory()->create();
        $user = User::factory()->create(['role' => 'admin']);
        $project = Project::factory()->create(['client_id' => $client->id, 'created_by' => $user->id]);
        $response = $this->actingAs($user)->put('/projects/'.$project->id, [
            'client_id' => $client->id,
            'name' => '更新後案件',
            'code' => $project->code,
            'status' => 'in_progress',
        ]);
        $response->assertRedirect('/projects');
        $this->assertDatabaseHas('projects', ['name' => '更新後案件']);
    }

    public function test_user_can_delete_project()
    {
        $client = Client::factory()->create();
        $user = User::factory()->create(['role' => 'admin']);
        $project = Project::factory()->create(['client_id' => $client->id, 'created_by' => $user->id]);
        $response = $this->actingAs($user)->delete('/projects/'.$project->id);
        $response->assertRedirect('/projects');
        $this->assertSoftDeleted('projects', ['id' => $project->id]);
    }

    public function test_validation_fails_with_invalid_data()
    {
        $user = User::factory()->create(['role' => 'admin']);
        $response = $this->actingAs($user)->post('/projects', [
            'client_id' => null,
            'name' => '',
            'status' => 'invalid',
        ]);
        $response->assertSessionHasErrors(['client_id', 'name', 'status']);
    }
}
