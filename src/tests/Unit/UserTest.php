<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_role_is_set_correctly()
    {
        $user = User::factory()->create(['role' => 'pm']);
        $this->assertEquals('pm', $user->role);
    }

    public function test_user_is_active_flag()
    {
        $user = User::factory()->create(['is_active' => false]);
        $this->assertFalse($user->is_active);
    }
}
