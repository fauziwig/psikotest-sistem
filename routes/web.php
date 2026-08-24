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

