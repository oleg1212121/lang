<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TrainingController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('startPage');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::get('/training', [TrainingController::class, "index"])->name('training');
Route::get('/change-priority/{ru}/{en}/{num}', [TrainingController::class, "changePriority"])->name('changePriority');
Route::get('/learn/{id}/{known}', [TrainingController::class, "learn"])->name('learn');
Route::get('/down/{id}', [TrainingController::class, "down"])->name('down');
Route::get('/info', [TrainingController::class, "info"])->name('info');
Route::get('/search', [TrainingController::class, "search"])->name('search');
Route::get('/associate/{ru}/{en}', [TrainingController::class, "associate"])->name('associate');
Route::get('/detach/{ru}/{en}', [TrainingController::class, "detach"])->name('detach');

require __DIR__.'/auth.php';
