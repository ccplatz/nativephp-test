<?php

namespace App\Livewire;

use App\Models\Todo;
use Livewire\Component;
use Livewire\Attributes\Rule;
use Carbon\Carbon;

class TodoList extends Component
{
    public $todos;
    public $completedTodos;

    #[Rule('required|min:3|max:255')]
    public $title = '';

    #[Rule('nullable|max:1000')]
    public $description = '';

    #[Rule('nullable|date_format:Y-m-d\TH:i')]
    public $due_date = '';

    #[Rule('nullable|in:pending,in_progress,completed')]
    public $status = 'pending';

    #[Rule('nullable|in:low,medium,high')]
    public $priority = 'medium';

    public $editingTodoId = null;
    public $isEditing = false;
    public $showCompletedTodos = true;

    public function mount(): void
    {
        $this->refreshTodos();
        $this->setDefaultDueDate();
    }

    public function refreshTodos(): void
    {
        $this->todos = Todo::where('status', '!=', 'completed')->latest()->get();
        $this->completedTodos = Todo::where('status', 'completed')->latest()->limit(5)->get();
    }

    public function setDefaultDueDate(): void
    {
        if (empty($this->due_date)) {
            $this->due_date = Carbon::now()->format('Y-m-d\TH:i');
        }
    }

    public function createTodo(): void
    {
        $this->validate();

        Todo::create([
            'title' => $this->title,
            'description' => $this->description,
            'due_date' => $this->due_date,
            'status' => $this->status,
            'priority' => $this->priority,
        ]);

        $this->reset(['title', 'description']);
        $this->setDefaultDueDate();
        $this->refreshTodos();

        session()->flash('message', 'Todo created successfully!');
    }

    public function editTodo($todoId): void
    {
        $this->isEditing = true;
        $this->editingTodoId = $todoId;

        $todo = Todo::find($todoId);

        $this->title = $todo->title;
        $this->description = $todo->description;
        $this->due_date = $todo->due_date ? Carbon::parse($todo->due_date)->format('Y-m-d\TH:i') : '';
        $this->status = $todo->status;
        $this->priority = $todo->priority;
    }

    public function updateTodo(): void
    {
        $this->validate();

        $todo = Todo::find($this->editingTodoId);

        $todo->update([
            'title' => $this->title,
            'description' => $this->description,
            'due_date' => $this->due_date,
            'status' => $this->status,
            'priority' => $this->priority,
        ]);

        $this->cancelEdit();
        $this->refreshTodos();

        session()->flash('message', 'Todo updated successfully!');
    }

    public function cancelEdit(): void
    {
        $this->isEditing = false;
        $this->editingTodoId = null;
        $this->reset(['title', 'description', 'status', 'priority']);
        $this->setDefaultDueDate();
    }

    public function deleteTodo($todoId): void
    {
        Todo::find($todoId)->delete();
        $this->refreshTodos();

        session()->flash('message', 'Todo deleted successfully!');
    }

    public function toggleStatus($todoId): void
    {
        $todo = Todo::find($todoId);

        $newStatus = match ($todo->status) {
            'pending' => 'in_progress',
            'in_progress' => 'completed',
            'completed' => 'pending',
            default => 'pending',
        };

        $todo->update(['status' => $newStatus]);
        $this->refreshTodos();
    }

    public function setCompleted($todoId): void
    {
        $todo = Todo::find($todoId);
        $todo->update(['status' => 'completed']);
        $this->refreshTodos();
    }

    public function toggleCompletedTodos(): void
    {
        $this->showCompletedTodos = !$this->showCompletedTodos;
    }

    public function render()
    {
        return view('livewire.todo-list');
    }
}
