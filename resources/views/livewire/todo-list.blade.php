<div class="max-w-4xl mx-auto my-8 p-6 bg-gray-900 rounded-lg">
    <h1 class="text-3xl font-bold mb-6 text-white">Todo List</h1>

    @if (session()->has('message'))
        <div class="bg-green-900 border border-green-700 text-green-300 px-4 py-3 rounded relative mb-6" role="alert">
            <span class="block sm:inline">{{ session('message') }}</span>
        </div>
    @endif

    <div class="bg-gray-800 shadow-md rounded-lg p-6 mb-8 border border-gray-700">
        <h2 class="text-xl font-semibold mb-4 text-white">{{ $isEditing ? 'Edit Todo' : 'Create New Todo' }}</h2>

        <form wire:submit.prevent="{{ $isEditing ? 'updateTodo' : 'createTodo' }}">
            <div class="mb-4">
                <label for="title" class="block text-gray-300 text-sm font-bold mb-2">Title</label>
                <input type="text" wire:model="title" id="title"
                    class="shadow bg-gray-700 appearance-none border border-gray-600 rounded w-full py-2 px-3 text-gray-100 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('title')
                    <span class="text-red-400 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="description" class="block text-gray-300 text-sm font-bold mb-2">Description</label>
                <textarea wire:model="description" id="description" rows="3"
                    class="shadow bg-gray-700 appearance-none border border-gray-600 rounded w-full py-2 px-3 text-gray-100 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                @error('description')
                    <span class="text-red-400 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="due_date" class="block text-gray-300 text-sm font-bold mb-2">Due Date</label>
                <input type="date" wire:model="due_date" id="due_date"
                    class="shadow bg-gray-700 appearance-none border border-gray-600 rounded w-full py-2 px-3 text-gray-100 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('due_date')
                    <span class="text-red-400 text-xs">{{ $message }}</span>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="status" class="block text-gray-300 text-sm font-bold mb-2">Status</label>
                    <select wire:model="status" id="status"
                        class="shadow bg-gray-700 appearance-none border border-gray-600 rounded w-full py-2 px-3 text-gray-100 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="pending">Pending</option>
                        <option value="in_progress">In Progress</option>
                        <option value="completed">Completed</option>
                    </select>
                    @error('status')
                        <span class="text-red-400 text-xs">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="priority" class="block text-gray-300 text-sm font-bold mb-2">Priority</label>
                    <select wire:model="priority" id="priority"
                        class="shadow bg-gray-700 appearance-none border border-gray-600 rounded w-full py-2 px-3 text-gray-100 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                    </select>
                    @error('priority')
                        <span class="text-red-400 text-xs">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-between">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">
                    {{ $isEditing ? 'Update Todo' : 'Create Todo' }}
                </button>

                @if ($isEditing)
                    <button type="button" wire:click="cancelEdit"
                        class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50">
                        Cancel
                    </button>
                @endif
            </div>
        </form>
    </div>

    <div class="bg-gray-800 shadow-md rounded-lg p-6 border border-gray-700">
        <h2 class="text-xl font-semibold mb-4 text-white">Your Todos</h2>

        @if (count($todos) > 0)
            <div class="space-y-4">
                @foreach ($todos as $todo)
                    <div
                        class="border border-gray-700 rounded-lg p-4 {{ $todo->status === 'completed' ? 'bg-gray-800 border-green-800' : ($todo->status === 'in_progress' ? 'bg-gray-800 border-blue-800' : 'bg-gray-800') }}">
                        <div class="flex items-start justify-between">
                            <div class="flex items-center gap-2">
                                @if ($todo->status === 'completed')
                                    <button class="flex-shrink-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                    </button>
                                @else
                                    <button wire:click="setCompleted({{ $todo->id }})"
                                        class="flex-shrink-0 hover:bg-gray-700 p-1 rounded transition-colors"
                                        title="Mark as completed">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="h-6 w-6 text-gray-500 hover:text-green-500" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </button>
                                @endif
                                <h3
                                    class="font-semibold text-lg {{ $todo->status === 'completed' ? 'line-through text-gray-500' : 'text-white' }}">
                                    {{ $todo->title }}</h3>
                            </div>

                            <div class="flex gap-2">
                                <button wire:click="editTodo({{ $todo->id }})"
                                    class="text-blue-400 hover:text-blue-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 0L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>
                                <button wire:click="deleteTodo({{ $todo->id }})"
                                    class="text-red-400 hover:text-red-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        @if ($todo->description)
                            <p class="mt-2 text-gray-300">{{ $todo->description }}</p>
                        @endif

                        <div class="mt-3 flex flex-wrap items-center gap-3 text-sm">
                            @if ($todo->due_date)
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full bg-gray-700 text-gray-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    {{ \Carbon\Carbon::parse($todo->due_date)->format('M d, Y') }}
                                </span>
                            @endif

                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full
                                {{ $todo->priority === 'high'
                                    ? 'bg-red-900 text-red-300'
                                    : ($todo->priority === 'medium'
                                        ? 'bg-yellow-900 text-yellow-300'
                                        : 'bg-green-900 text-green-300') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                                {{ ucfirst($todo->priority) }} Priority
                            </span>

                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full
                                {{ $todo->status === 'completed'
                                    ? 'bg-green-900 text-green-300'
                                    : ($todo->status === 'in_progress'
                                        ? 'bg-blue-900 text-blue-300'
                                        : 'bg-gray-700 text-gray-300') }}">
                                {{ ucfirst(str_replace('_', ' ', $todo->status)) }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-gray-700 p-4 rounded border border-gray-600 text-center">
                <p class="text-gray-300">No todos yet. Create one to get started!</p>
            </div>
        @endif

        @if (count($completedTodos) > 0)
            <div class="mt-8">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-white">Recently Completed</h3>
                    <button wire:click="toggleCompletedTodos" class="text-gray-400 hover:text-gray-300">
                        @if ($showCompletedTodos)
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        @endif
                    </button>
                </div>

                @if ($showCompletedTodos)
                    <div class="bg-gray-700 rounded-lg p-4 border border-gray-600">
                        <ul class="space-y-2">
                            @foreach ($completedTodos as $completedTodo)
                                <li class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-green-500"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-gray-400 line-through">{{ $completedTodo->title }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        @endif
    </div>
</div>
