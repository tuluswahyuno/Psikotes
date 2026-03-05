<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\TestController as AdminTestController;
use App\Http\Controllers\Admin\QuestionController as AdminQuestionController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ResultController as AdminResultController;
use App\Http\Controllers\Peserta\DashboardController as PesertaDashboardController;
use App\Http\Controllers\Peserta\TestSessionController;
use App\Http\Controllers\Peserta\ResultController as PesertaResultController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Redirect dashboard based on role
Route::get('/dashboard', function () {
    if (auth()->user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('peserta.dashboard');
})->middleware(['auth'])->name('dashboard');

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('tests', AdminTestController::class);
    Route::get('/tests/{test}/questions/create', [AdminQuestionController::class, 'create'])->name('questions.create');
    Route::post('/tests/{test}/questions', [AdminQuestionController::class, 'store'])->name('questions.store');
    Route::get('/tests/{test}/questions/{question}/edit', [AdminQuestionController::class, 'edit'])->name('questions.edit');
    Route::put('/tests/{test}/questions/{question}', [AdminQuestionController::class, 'update'])->name('questions.update');
    Route::delete('/tests/{test}/questions/{question}', [AdminQuestionController::class, 'destroy'])->name('questions.destroy');
    Route::resource('users', AdminUserController::class)->except(['show']);
    Route::get('/users/{user}/assign', [AdminUserController::class, 'assign'])->name('users.assign');
    Route::post('/users/{user}/assign', [AdminUserController::class, 'storeAssignment'])->name('users.assign.store');
    Route::get('/results', [AdminResultController::class, 'index'])->name('results.index');
    Route::get('/results/{result}', [AdminResultController::class, 'show'])->name('results.show');
});

// Peserta Routes
Route::middleware(['auth', 'role:peserta'])->prefix('peserta')->name('peserta.')->group(function () {
    Route::get('/dashboard', [PesertaDashboardController::class, 'index'])->name('dashboard');
    Route::get('/tests/{test}', [TestSessionController::class, 'show'])->name('tests.show');
    Route::post('/tests/{test}/start', [TestSessionController::class, 'start'])->name('tests.start');
    Route::get('/tests/{test}/attempt/{session}', [TestSessionController::class, 'attempt'])->name('tests.attempt');
    Route::post('/sessions/{session}/answer', [TestSessionController::class, 'saveAnswer'])->name('sessions.answer');
    Route::post('/sessions/{session}/submit', [TestSessionController::class, 'submit'])->name('sessions.submit');
    Route::get('/results', [PesertaResultController::class, 'index'])->name('results.index');
    Route::get('/results/{result}', [PesertaResultController::class, 'show'])->name('results.show');
});

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
