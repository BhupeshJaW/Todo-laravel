@extends('layout')

@section('content')
<style>
/* Fade-out animation for new task highlight */
.fade-highlight {
    animation: fadeOut 3s ease-out forwards;
    background-color: #dcfce7; /* Tailwind's bg-green-50 */
}
@keyframes fadeOut {
    0% { background-color: #dcfce7; }
    100% { background-color: transparent; }
}
</style>

<div class="card w-full">
    <section>
        {{-- New Task Form --}}
        <form class="form grid gap-6" method="POST" action="{{ route('tasks.store') }}">
            @csrf
            {{-- Task Description Input --}}
            <div class="grid gap-2">
                <label for="task_description">Description</label>
                <div class="grid grid-cols-[1fr_180px] gap-2">
                    <input type="text" id="task_description" name="description" placeholder="describe the task..." tabindex="1" autofocus value="{{ old('description') }}">
                    <button type="submit" class="btn" tabindex="3">Add</button>
                </div>
                {{-- Display validation error for description --}}
                @error('description')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>
            {{-- Task Due Date Input --}}
            <div class="grid gap-2">
                <label for="task_due_date">Due date</label>
                <input type="date" id="task_due_date" name="due_date" tabindex="2" value="{{ old('due_date') }}">
                {{-- Display validation error for due_date --}}
                @error('due_date')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>
        </form>
    </section>

    <hr>

    <section>
        {{-- Task Filter Form --}}
        <form class="form flex gap-2 mb-6" method="GET" action="{{ route('tasks.index') }}">
            <label for="filter_status">Filter tasks</label>
            <select id="filter_status" name="status">
                <option value="" {{ !$status ? 'selected' : '' }}>All</option>
                <option value="open" {{ $status === 'open' ? 'selected' : '' }}>Open</option>
                <option value="overdue" {{ $status === 'overdue' ? 'selected' : '' }}>Overdue</option>
            </select>
            <button type="submit" class="btn">Filter</button>
        </form>
        {{-- Task List --}}
        <ul class="grid gap-4">
            @foreach($tasks as $task)
                {{-- Highlight new task --}}
                <li id="task-{{ $task->id }}" class="flex items-center gap-4 {{ session('new_task_id') == $task->id ? 'fade-highlight' : '' }}">
                    <div class="flex flex-col gap-1 mr-auto" onclick="editTask({{ $task->id }})" style="cursor: pointer;">
                        <p class="text-sm font-medium leading-none {{ $task->done ? 'line-through' : '' }}" id="desc-{{ $task->id }}">
                            {{ $task->description }}
                        </p>
                        {{-- Show due date or "No due date" fallback --}}
                        @if($task->due_date)
                            <p class="text-sm font-muted leading-none {{ $task->done ? 'line-through' : '' }}" id="date-{{ $task->id }}">
                                {{ $task->due_date->format('d-m-Y') }}
                            </p>
                        @else
                            <p class="text-sm font-muted leading-none {{ $task->done ? 'line-through' : '' }}" id="date-{{ $task->id }}">
                                No due date
                            </p>
                        @endif
                    </div>
                    {{-- Mark as Done Button (only if not done) --}}
                    @if(!$task->done)
                    <form class="form" method="POST" action="{{ route('tasks.update', $task) }}">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="{{ $status }}">
                        <input type="hidden" name="done" value="1">
                        <button type="submit" class="btn-sm-outline">Done</button>
                    </form>
                    @endif
                </li>
            @endforeach
        </ul>
    </section>
</div>
<script>
/**
 * ✏️ editTask()
 * Converts a static task view into an inline editable form (description + due_date).
 * Keeps UX smooth by replacing only the clicked task with a form.
 */
function editTask(taskId) {
    const descEl = document.getElementById(`desc-${taskId}`);
    const dateEl = document.getElementById(`date-${taskId}`);

    if (!descEl || !dateEl) return; // prevent null errors

    const descText = descEl.textContent.trim();
    const dateText = dateEl.textContent.trim();

    // Create editable form dynamically
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/tasks/${taskId}`;
    form.className = 'flex flex-col gap-2 w-full';

    // Add CSRF + PATCH
    form.innerHTML = `
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="_method" value="PATCH">
        <input type="hidden" name="status" value="{{ $status }}">

        <input type="text" name="description" value="${descText}" class="border p-1 rounded text-sm w-full" required>
        <!-- Convert 'd-m-Y' to 'Y-m-d' for input[type=date] compatibility -->
        <input type="date" name="due_date" value="${dateText !== 'No due date' ? new Date(dateText.split('-').reverse().join('-')).toISOString().split('T')[0] : ''}" class="border p-1 rounded text-sm w-full">
        <div class="flex gap-2 mt-1 items-center">
            <button type="submit" class="btn-sm">Save</button>
            <button type="button" class="text-sm btn-sm" title="Close edit" onclick="closeEdit(event, ${taskId}, '${descText}', '${dateText}')">Close</button>
        </div>
    `; 
    // Replace the static display wrapper with the new editable form
    const wrapper = descEl.parentElement;
    wrapper.replaceWith(form); 
    // Automatically focus description for quicker editing
    form.querySelector('input[name="description"]').focus();
}

/**
 * ❌ closeEdit()
 * Reverts the editable form back to its original display (description + date)
 * without submitting any changes.
 */
function closeEdit(e, taskId, descText, dateText) {
    e.stopPropagation(); // stop click from triggering parent editTask()

    const form = e.target.closest('form');
    const wrapper = document.createElement('div');
    wrapper.className = 'flex flex-col gap-1 mr-auto';
    wrapper.setAttribute('onclick', `editTask(${taskId})`);
    wrapper.style.cursor = 'pointer';
    // Restore original text view
    wrapper.innerHTML = `
        <p class="text-sm font-medium leading-none" id="desc-${taskId}">${descText}</p>
        <p class="text-sm font-muted leading-none" id="date-${taskId}">${dateText}</p>
    `;
    // Replace form with static display
    form.replaceWith(wrapper);
}
</script>

@endsection
