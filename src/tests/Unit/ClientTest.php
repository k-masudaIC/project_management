<?php

namespace Tests\Unit;

use App\Models\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientTest extends TestCase
{
    use RefreshDatabase;

    public function test_client_name_is_required()
    {
        $this->expectException(\Illuminate\Database\QueryException::class);
        Client::create(['company_name' => 'テスト株式会社']);
    }

    public function test_client_company_name_is_set()
    {
        $client = Client::factory()->create(['company_name' => 'テスト株式会社']);
        $this->assertEquals('テスト株式会社', $client->company_name);
    }
}
