<?php
namespace Database\Factories;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'created_by' => function () {
                return \App\Models\User::factory()->create()->id;
            },
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->optional()->paragraph(),
            'status' => $this->faker->randomElement(['not_started','in_progress','in_review','completed','on_hold']),
            'priority' => $this->faker->randomElement(['low','medium','high']),
            'estimated_hours' => $this->faker->randomFloat(2, 1, 40),
            'start_date' => $this->faker->optional()->date(),
            'due_date' => $this->faker->optional()->date(),
            'sort_order' => $this->faker->numberBetween(1, 100),
        ];
    }
}
