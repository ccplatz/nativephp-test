<?php

use App\Models\Todo;

Schedule::call(function () {
    $todosToNotify = Todo::where('status', '!=', 'completed')
        ->where('notified_at', null)
        ->where('due_date', '<=', now())
        ->get();

    foreach ($todosToNotify as $todo) {
        Notification::title('Todo Expired')
            ->message("“{$todo->title}” was due at {$todo->due_date->toDateTimeString()}")
            ->show();

        $todo->notified_at = now()->toDateTimeString();
        $todo->save();
    }
})->everyMinute();
