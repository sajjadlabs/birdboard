<?php

use App\Http\Controllers\ProjectController;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('login');

Route::controller(ProjectController::class)->middleware('auth')->group(function () {
    Route::get('/projects', 'index');
    Route::post('/projects', 'store');
    Route::get('projects/{project}', 'show');
});
