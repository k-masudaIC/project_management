<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_monthly_report()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $response = $this->actingAs($admin)->get('/reports/monthly');
        $response->assertStatus(200)->assertSee('月次工数レポート');
    }

    public function test_pm_can_view_project_report()
    {
        $pm = User::factory()->create(['role' => 'pm']);
        $response = $this->actingAs($pm)->get('/reports/project');
        $response->assertStatus(200)->assertSee('案件別収支レポート');
    }

    public function test_member_cannot_view_report()
    {
        $member = User::factory()->create(['role' => 'member']);
        $response = $this->actingAs($member)->get('/reports/monthly');
        $response->assertForbidden();
    }

    public function test_guest_redirected_to_login()
    {
        $response = $this->get('/reports/monthly');
        $response->assertRedirect('/login');
    }
}
