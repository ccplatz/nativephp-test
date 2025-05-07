@extends('layouts.app')

@php
    use App\Models\Todo;
@endphp

<h1>Todolist</h1>
<button class="btn btn-primary">Button</button>
@forelse (Todo::all() as $todo)
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $todo->title }}</h5>
            <p class="card-text">{{ $todo->description }}</p>
            <a href="#" class="btn btn-primary">Go somewhere</a>
        </div>
    </div>
@empty
    <p>No todos found.</p>
@endforelse
