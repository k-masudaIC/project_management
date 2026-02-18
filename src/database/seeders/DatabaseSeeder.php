<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ユーザー
        \App\Models\User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'admin',
        ]);
        \App\Models\User::factory(5)->create();

        // クライアント
        \App\Models\Client::factory(5)->create();

        // 案件（projects）
        \App\Models\Project::factory(20)->create();

        // タスク（tasks）
        $this->call(TaskSeeder::class);
    }
}
