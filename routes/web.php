<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {
    Route::resource('tasks', TaskController::class);
    Route::post('/tasks/{task}/generate-link', [TaskController::class, 'generateLink'])
        ->name('tasks.generate_link');
});

Route::get('/tasks/shared/{token}', [TaskController::class, 'showSharedTask'])
    ->name('tasks.shared');
