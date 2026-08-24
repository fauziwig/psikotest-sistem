<?php

use App\Http\Controllers\CandidateAssessmentController;
use App\Models\Assessment;
use Illuminate\Support\Facades\Route;

// Redirect root to first published assessment or fallback
Route::get('/', function () {
    try {
        $published = Assessment::where('is_published', true)->first();
        if ($published) {
            return redirect()->route('candidate.register', $published->slug);
        }
    } catch (\Throwable $e) {
        // Fallback if table doesn't exist yet
    }
    return view('welcome');
});

// Candidate Assessment Routes
Route::prefix('assessment/{slug}')->name('candidate.')->group(function () {
    Route::get('/', [CandidateAssessmentController::class, 'showRegister'])->name('register');
    Route::post('/start', [CandidateAssessmentController::class, 'start'])->name('start');
    Route::get('/take', [CandidateAssessmentController::class, 'take'])->name('take');
    Route::post('/submit', [CandidateAssessmentController::class, 'submit'])->name('submit');
    Route::get('/completed', [CandidateAssessmentController::class, 'completed'])->name('completed');
});

// Admin Authentication Routes
Route::get('/login', fn() => redirect()->route('admin.login'))->name('login');

Route::prefix('admin')->group(function () {
    Route::get('/login', [\App\Http\Controllers\Admin\AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [\App\Http\Controllers\Admin\AdminAuthController::class, 'login'])->name('admin.login.submit');
    Route::post('/logout', [\App\Http\Controllers\Admin\AdminAuthController::class, 'logout'])->name('admin.logout');

    // Protected Admin Routes
    Route::middleware('auth')->name('admin.')->group(function () {
        Route::get('/', function () {
            return redirect()->route('admin.dashboard');
        });
        Route::get('/dashboard', [\App\Http\Controllers\Admin\AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/submissions', [\App\Http\Controllers\Admin\AdminSubmissionController::class, 'index'])->name('submissions.index');
        Route::get('/submissions/{id}', [\App\Http\Controllers\Admin\AdminSubmissionController::class, 'show'])->name('submissions.show');
        Route::get('/assessments', [\App\Http\Controllers\Admin\AdminAssessmentController::class, 'index'])->name('assessments.index');
        Route::put('/assessments/{id}', [\App\Http\Controllers\Admin\AdminAssessmentController::class, 'update'])->name('assessments.update');
        Route::get('/branding', [\App\Http\Controllers\Admin\AdminBrandingController::class, 'index'])->name('branding.index');
        Route::post('/branding', [\App\Http\Controllers\Admin\AdminBrandingController::class, 'update'])->name('branding.update');
    });
});

