<?php

namespace Database\Seeders;

use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    private array $tasks = [
        [
            'description' => 'Fix bug described in Jira ticket WBA-12',
            'due_date' => 'next monday',
            'done' => false,
        ],
        [
            'description' => 'Refactor the payment module',
            'done' => true,
        ],
        [
            'description' => 'Code review for the new authentication system',
            'done' => false,
        ],
        [
            'description' => 'Update documentation for the API endpoints',
            'due_date' => 'next friday',
            'done' => true,
        ],
        [
            'description' => 'Implement feature request from customer feedback',
            'due_date' => 'last tuesday',
            'done' => false,
        ],
        [
            'description' => 'Optimize database queries for better performance',
            'done' => false,
        ],
        [
            'description' => 'Deploy new version to production',
            'due_date' => 'last wednesday',
            'done' => true,
        ],
        [
            'description' => 'Setup CI/CD pipeline for new project',
            'due_date' => 'tomorrow',
            'done' => false,
        ],
        [
            'description' => 'Prepare slides for the tech talk',
            'done' => true,
        ],
        [
            'description' => 'Investigate reported security vulnerability',
            'due_date' => 'today',
            'done' => true,
        ],
    ];

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $tasks = array_map(function ($task) {
            if (array_key_exists('due_date', $task)) {
                $task['due_date'] = Carbon::parse($task['due_date']);
            }

            return $task;
        }, $this->tasks);

        foreach ($tasks as $task) {
            Task::factory()->create($task);
        }
    }
}
