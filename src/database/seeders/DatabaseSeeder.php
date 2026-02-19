<?php


namespace Database\Seeders;

use Illuminate\Support\Facades\Hash;

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
            'password' => Hash::make('password'),
        ]);
        \App\Models\User::factory(5)->create();

        // クライアント
        \App\Models\Client::factory(10)->create();

        // プロジェクト（projects）
        \App\Models\Project::factory(30)->create();

        // タスク（tasks）
        \App\Models\Project::all()->each(function ($project) {
            \App\Models\Task::factory(8)->create([
                'project_id' => $project->id,
                'created_by' => \App\Models\User::inRandomOrder()->first()->id,
            ]);
        });

        $this->call(TaskAssignmentSeeder::class);
        $this->call(TimeEntrySeeder::class);
    }
}
