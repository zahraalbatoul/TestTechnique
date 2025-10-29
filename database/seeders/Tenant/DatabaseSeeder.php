<?php

namespace Database\Seeders\Tenant;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds for tenant database.
     */
    public function run(): void
    {
        // Create sample projects
        $project1 = Project::create([
            'name' => 'Website Redesign',
            'description' => 'Complete redesign of company website',
            'status' => 'in_progress',
        ]);

        $project2 = Project::create([
            'name' => 'Mobile App',
            'description' => 'Develop mobile application',
            'status' => 'planning',
        ]);

        // Create sample tasks
        Task::create([
            'project_id' => $project1->id,
            'title' => 'Create wireframes',
            'description' => 'Design wireframes for all pages',
            'status' => 'done',
            'priority' => 'high',
        ]);

        Task::create([
            'project_id' => $project1->id,
            'title' => 'Implement frontend',
            'description' => 'Build responsive frontend',
            'status' => 'in_progress',
            'priority' => 'high',
        ]);

        Task::create([
            'project_id' => $project2->id,
            'title' => 'Plan architecture',
            'description' => 'Design app architecture',
            'status' => 'todo',
            'priority' => 'medium',
        ]);
    }
}
