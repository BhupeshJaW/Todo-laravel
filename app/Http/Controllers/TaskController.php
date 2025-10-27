<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;

class TaskController extends Controller
{
    /**
     * Display task list with filters.
     */
    public function index(Request $request)
    {
        $status = $request->query('status');

        $tasks = Task::query()
            ->orderByUrgency()
            ->when($status === 'overdue', fn($q) => $q->overdue())
            ->when($status === 'open', fn($q) => $q->open())
            ->get();

        return view('tasks.index', compact('tasks', 'status'));
    }

    /**
     * Store a newly created task.
     */
    public function store(StoreTaskRequest $request)
    {
        $task = Task::create($request->validated());

        return redirect('/')
            ->with('new_task_id', $task->id);
    }

    /**
     * Update an existing task (mark as done or edit).
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $task->update($request->validated());

        return redirect()
            ->route('tasks.index', ['status' => $request->input('status')])
            ->with('success', 'Task updated successfully.');
    }
}
