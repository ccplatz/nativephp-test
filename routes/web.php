<?php

use Illuminate\Support\Facades\Route;

Route::get('/', \App\Livewire\TodoList::class)->name('todolist');
Route::get('/todo-completed', \App\Livewire\TodoCompleted::class)->name('todo-completed');
