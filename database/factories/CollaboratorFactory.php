<?php

namespace Database\Factories;

use App\Models\Collaborator;
use App\Models\Project;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CollaboratorFactory extends Factory
{
    protected $model = Collaborator::class;

    protected static $pairs = [];

    public function definition(): array
    {
        if (empty(self::$pairs)) {
            $users = User::where('role_id', Role::STUDENT)->pluck('id')->toArray();
            $projects = Project::pluck('id')->toArray();

            foreach ($users as $u) {
                foreach ($projects as $p) {
                    self::$pairs[] = ['user_id' => $u, 'project_id' => $p];
                }
            }

            shuffle(self::$pairs);
        }

        $pair = array_pop(self::$pairs);

        return [
            'user_id' => $pair['user_id'],
            'project_id'  => $pair['project_id'],
            'role' => fake()->text(20),
            'scholarship' => fake()->randomFloat(2, 0, 200),
        ];
    }
}
