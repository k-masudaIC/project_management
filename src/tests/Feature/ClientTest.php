<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_clients(): void
    {
        $response = $this->get('/clients');
        $response->assertRedirect('/login');
    }

    public function test_user_can_view_client_list(): void
    {
        $user = User::factory()->create();
        Client::factory()->count(3)->create();
        $response = $this->actingAs($user)->get('/clients');
        $response->assertStatus(200)->assertSee('クライアント一覧');
    }

    public function test_admin_can_create_client(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $data = [
            'name' => '担当者A',
            'company_name' => '株式会社A',
            'email' => 'a@example.com',
            'phone' => '090-1234-5678',
            'address' => '東京都',
            'notes' => 'メモ',
            'is_active' => true,
        ];
        $response = $this->actingAs($admin)->post('/clients', $data);
        $response->assertRedirect('/clients');
        $this->assertDatabaseHas('clients', ['company_name' => '株式会社A']);
    }

    public function test_pm_can_update_client(): void
    {
        $pm = User::factory()->create(['role' => 'pm']);
        $client = Client::factory()->create(['company_name' => '旧社名']);
        $response = $this->actingAs($pm)->put('/clients/'.$client->id, [
            'name' => $client->name,
            'company_name' => '新社名',
            'email' => $client->email,
            'phone' => $client->phone,
            'address' => $client->address,
            'notes' => $client->notes,
            'is_active' => $client->is_active,
        ]);
        $response->assertRedirect('/clients');
        $this->assertDatabaseHas('clients', ['company_name' => '新社名']);
    }

    public function test_member_cannot_delete_client(): void
    {
        $member = User::factory()->create(['role' => 'member']);
        $client = Client::factory()->create();
        $response = $this->actingAs($member)->delete('/clients/'.$client->id);
        $response->assertForbidden();
    }
}
