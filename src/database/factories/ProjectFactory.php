<?php
namespace Database\Factories;

use App\Models\Project;
use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition(): array
    {
        return [
            'client_id' => Client::factory(),
            'name' => $this->faker->words(3, true),
            'code' => 'PRJ-' . $this->faker->unique()->numerify('2026-###'),
            'description' => $this->faker->sentence(),
            'status' => 'proposal',
            'budget' => $this->faker->numberBetween(100000, 1000000),
            'estimated_hours' => $this->faker->randomFloat(2, 10, 100),
            'start_date' => $this->faker->date(),
            'end_date' => $this->faker->date(),
            'created_by' => User::factory(),
        ];
    }
}
