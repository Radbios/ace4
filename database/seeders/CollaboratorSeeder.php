<?php

namespace Database\Seeders;

use App\Models\Collaborator;
use Illuminate\Database\Seeder;

class CollaboratorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Collaborator::factory(30)->create();
    }
}
