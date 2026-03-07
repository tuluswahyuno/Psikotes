<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\TestController as AdminTestController;
use App\Http\Controllers\Admin\QuestionController as AdminQuestionController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ResultController as AdminResultController;
use App\Http\Controllers\Admin\SkdPackageController as AdminSkdPackageController;
use App\Http\Controllers\Admin\SkdResultController as AdminSkdResultController;
use App\Http\Controllers\Peserta\DashboardController as PesertaDashboardController;
use App\Http\Controllers\Peserta\TestSessionController;
use App\Http\Controllers\Peserta\ResultController as PesertaResultController;
use App\Http\Controllers\Peserta\SkdController;
use App\Http\Controllers\Peserta\LearningController;
use App\Http\Controllers\Peserta\PracticeController;
use App\Http\Controllers\Admin\LearningController as AdminLearningController;
use App\Http\Controllers\Admin\PracticeQuestionController as AdminPracticeQuestionController;
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

    // SKD Routes
    Route::resource('skd-packages', AdminSkdPackageController::class);
    Route::get('/skd-results', [AdminSkdResultController::class, 'index'])->name('skd-results.index');
    Route::get('/skd-results/{skdResult}', [AdminSkdResultController::class, 'show'])->name('skd-results.show');

    // Learning Content Bank Routes
    Route::prefix('learning')->name('learning.')->group(function () {
        Route::get('/sections', [AdminLearningController::class, 'sections'])->name('sections');
        // Sub-Topics
        Route::get('/sections/{section}/sub-topics', [AdminLearningController::class, 'subTopics'])->name('sub-topics');
        Route::get('/sections/{section}/sub-topics/create', [AdminLearningController::class, 'createSubTopic'])->name('sub-topics.create');
        Route::post('/sections/{section}/sub-topics', [AdminLearningController::class, 'storeSubTopic'])->name('sub-topics.store');
        Route::get('/sections/{section}/sub-topics/{subTopic}/edit', [AdminLearningController::class, 'editSubTopic'])->name('sub-topics.edit');
        Route::put('/sections/{section}/sub-topics/{subTopic}', [AdminLearningController::class, 'updateSubTopic'])->name('sub-topics.update');
        Route::delete('/sections/{section}/sub-topics/{subTopic}', [AdminLearningController::class, 'destroySubTopic'])->name('sub-topics.destroy');
        // Materials
        Route::get('/sections/{section}/sub-topics/{subTopic}/materials', [AdminLearningController::class, 'materials'])->name('materials');
        Route::get('/sections/{section}/sub-topics/{subTopic}/materials/create', [AdminLearningController::class, 'createMaterial'])->name('materials.create');
        Route::post('/sections/{section}/sub-topics/{subTopic}/materials', [AdminLearningController::class, 'storeMaterial'])->name('materials.store');
        Route::get('/sections/{section}/sub-topics/{subTopic}/materials/{material}/edit', [AdminLearningController::class, 'editMaterial'])->name('materials.edit');
        Route::put('/sections/{section}/sub-topics/{subTopic}/materials/{material}', [AdminLearningController::class, 'updateMaterial'])->name('materials.update');
        Route::delete('/sections/{section}/sub-topics/{subTopic}/materials/{material}', [AdminLearningController::class, 'destroyMaterial'])->name('materials.destroy');
    });

    // Practice Questions Bank Routes
    Route::prefix('practice-questions')->name('practice-questions.')->group(function () {
        Route::get('/', [AdminPracticeQuestionController::class, 'index'])->name('index');
        Route::get('/create', [AdminPracticeQuestionController::class, 'create'])->name('create');
        Route::post('/', [AdminPracticeQuestionController::class, 'store'])->name('store');
        Route::get('/{practiceQuestion}/edit', [AdminPracticeQuestionController::class, 'edit'])->name('edit');
        Route::put('/{practiceQuestion}', [AdminPracticeQuestionController::class, 'update'])->name('update');
        Route::delete('/{practiceQuestion}', [AdminPracticeQuestionController::class, 'destroy'])->name('destroy');
        Route::get('/import-form', [AdminPracticeQuestionController::class, 'importForm'])->name('import.form');
        Route::post('/import', [AdminPracticeQuestionController::class, 'import'])->name('import');
        Route::get('/template/download', [AdminPracticeQuestionController::class, 'downloadTemplate'])->name('template.download');
    });
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

    // SKD Routes
    Route::get('/skd', [SkdController::class, 'index'])->name('skd.index');
    Route::get('/skd/{skdPackage}', [SkdController::class, 'show'])->name('skd.show');
    Route::post('/skd/{skdPackage}/start', [SkdController::class, 'start'])->name('skd.start');
    Route::get('/skd/{skdPackage}/attempt/{skdSession}', [SkdController::class, 'attempt'])->name('skd.attempt');
    Route::post('/skd-sessions/{skdSession}/answer', [SkdController::class, 'saveAnswer'])->name('skd.answer');
    Route::post('/skd-sessions/{skdSession}/submit', [SkdController::class, 'submit'])->name('skd.submit');
    Route::get('/skd-results', [SkdController::class, 'results'])->name('skd.results');
    Route::get('/skd-results/{skdResult}', [SkdController::class, 'resultShow'])->name('skd.results.show');
    Route::get('/skd-results/{skdResult}/review', [SkdController::class, 'review'])->name('skd.results.review');
    Route::get('/skd-leaderboard', [SkdController::class, 'leaderboard'])->name('skd.leaderboard');

    // Learning Routes
    Route::get('/learn', [LearningController::class, 'index'])->name('learn.index');
    Route::get('/learn/{section:slug}', [LearningController::class, 'section'])->name('learn.section');
    Route::get('/learn/{section:slug}/{subTopic:slug}', [LearningController::class, 'material'])->name('learn.material');
    Route::post('/learn/complete-material', [LearningController::class, 'completeMaterial'])->name('learn.complete');

    // Practice Routes
    Route::post('/practice/submit', [PracticeController::class, 'submit'])->name('practice.submit');
    Route::get('/practice/result/{attempt}', [PracticeController::class, 'result'])->name('practice.result');
    Route::get('/practice/{section:slug}/{subTopic:slug}', [PracticeController::class, 'start'])->name('practice.start');
});

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
