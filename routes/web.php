<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectTasksController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::controller(ProjectController::class)->middleware('auth')->group(function () {
    Route::get('/projects', 'index');
    Route::get('/projects/create', 'create');
    Route::post('/projects', 'store');
    Route::get('projects/{project}', 'show');
});

Route::post('/projects/{project}/tasks', [ProjectTasksController::class, 'store']);

require __DIR__.'/auth.php';
