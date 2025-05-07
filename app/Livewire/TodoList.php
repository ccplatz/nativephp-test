<?php

namespace App\Livewire;

use App\Models\Todo;
use Livewire\Component;
use Livewire\Attributes\Rule;

class TodoList extends Component
{
    public $todos;
    public $completedTodos;

    #[Rule('required|min:3|max:255')]
    public $title = '';

    #[Rule('nullable|max:1000')]
    public $description = '';

    #[Rule('nullable|date')]
    public $due_date = '';

    #[Rule('nullable|in:pending,in_progress,completed')]
    public $status = 'pending';

    #[Rule('nullable|in:low,medium,high')]
    public $priority = 'medium';

    public $editingTodoId = null;
    public $isEditing = false;
    public $showCompletedTodos = true;

    public function mount()
    {
        $this->refreshTodos();
    }

    public function refreshTodos()
    {
        $this->todos = Todo::where('status', '!=', 'completed')->latest()->get();
        $this->completedTodos = Todo::where('status', 'completed')->latest()->limit(5)->get();
    }

    public function createTodo()
    {
        $this->validate();

        Todo::create([
            'title' => $this->title,
            'description' => $this->description,
            'due_date' => $this->due_date,
            'status' => $this->status,
            'priority' => $this->priority,
        ]);

        $this->reset(['title', 'description', 'due_date']);
        $this->refreshTodos();

        session()->flash('message', 'Todo created successfully!');
    }

    public function editTodo($todoId)
    {
        $this->isEditing = true;
        $this->editingTodoId = $todoId;

        $todo = Todo::find($todoId);

        $this->title = $todo->title;
        $this->description = $todo->description;
        $this->due_date = $todo->due_date;
        $this->status = $todo->status;
        $this->priority = $todo->priority;
    }

    public function updateTodo()
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

    public function cancelEdit()
    {
        $this->isEditing = false;
        $this->editingTodoId = null;
        $this->reset(['title', 'description', 'due_date', 'status', 'priority']);
    }

    public function deleteTodo($todoId)
    {
        Todo::find($todoId)->delete();
        $this->refreshTodos();

        session()->flash('message', 'Todo deleted successfully!');
    }

    public function toggleStatus($todoId)
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

    public function setCompleted($todoId)
    {
        $todo = Todo::find($todoId);
        $todo->update(['status' => 'completed']);
        $this->refreshTodos();
    }

    public function toggleCompletedTodos()
    {
        $this->showCompletedTodos = !$this->showCompletedTodos;
    }

    public function render()
    {
        return view('livewire.todo-list');
    }
}
