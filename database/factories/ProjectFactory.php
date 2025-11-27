<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    protected $model = Project::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->sentence(3),
            'price' => fake()->randomFloat(2, 10, 500),
            'description' => fake()->paragraph(),
            'category' => fake()->word(),
            'coordinator_id' => User::where('role_id', Role::COORDINATOR)->inRandomOrder()->first()->id,
            'is_subscription' => fake()->boolean(),
            'max_collaborators' => fake()->numberBetween(1, 30),
        ];
    }
}
