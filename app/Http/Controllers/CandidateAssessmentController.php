<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\Candidate;
use App\Models\CandidateSubmission;
use App\Models\CompanySetting;
use App\Services\DiscScoringService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CandidateAssessmentController extends Controller
{
    public function __construct(
        protected DiscScoringService $scoringService
    ) {}

    /**
     * Ambil pengaturan branding perusahaan.
     */
    protected function getCompanySetting(): CompanySetting
    {
        return CompanySetting::first() ?? new CompanySetting([
            'company_name' => config('app.name', 'Assessment Sistem'),
            'primary_color' => '#2563eb',
            'secondary_color' => '#475569',
        ]);
    }

    /**
     * Tampilkan halaman registrasi data diri dan instruksi pengerjaan.
     */
    public function showRegister(string $slug): View
    {
        $assessment = Assessment::where('slug', $slug)
            ->where('is_published', true)
            ->withCount('questions')
            ->firstOrFail();

        $companySetting = $this->getCompanySetting();

        return view('candidate.register', compact('assessment', 'companySetting'));
    }

    /**
     * Proses pendaftaran data diri kandidat dan inisialisasi sesi pengerjaan.
     */
    public function start(Request $request, string $slug): RedirectResponse
    {
        $assessment = Assessment::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'whatsapp_number' => ['required', 'string', 'max:50'],
            'applied_position' => ['required', 'string', 'max:255'],
            'source_platform' => ['required', 'string', 'max:255'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'whatsapp_number.required' => 'Nomor WhatsApp wajib diisi.',
            'applied_position.required' => 'Posisi yang dilamar wajib diisi.',
            'source_platform.required' => 'Platform info lamaran wajib dipilih/diisi.',
        ]);

        $candidate = Candidate::create($validated);

        $submission = CandidateSubmission::create([
            'assessment_id' => $assessment->id,
            'candidate_id' => $candidate->id,
            'started_at' => now(),
            'is_time_out' => false,
            'answers_payload' => [],
            'disc_scores' => [],
        ]);

        session([
            'active_submission_id' => $submission->id,
            'active_assessment_slug' => $slug,
        ]);

        return redirect()->route('candidate.take', $slug);
    }

    /**
     * Tampilkan halaman test runner pengerjaan soal dengan countdown timer.
     */
    public function take(Request $request, string $slug): View|RedirectResponse
    {
        $assessment = Assessment::where('slug', $slug)
            ->where('is_published', true)
            ->with(['questions.options'])
            ->firstOrFail();

        $submissionId = session('active_submission_id');

        if (!$submissionId) {
            return redirect()->route('candidate.register', $slug)
                ->with('error', 'Sesi pengerjaan belum dimulai. Silakan isi data diri terlebih dahulu.');
        }

        $submission = CandidateSubmission::where('id', $submissionId)
            ->where('assessment_id', $assessment->id)
            ->with('candidate')
            ->firstOrFail();

        // Jika sudah pernah disubmit
        if ($submission->submitted_at) {
            return redirect()->route('candidate.completed', $slug);
        }

        // Hitung sisa waktu dalam detik
        $durationSeconds = $assessment->duration_minutes * 60;
        $elapsedSeconds = Carbon::now()->diffInSeconds($submission->started_at);
        $remainingSeconds = max(0, $durationSeconds - $elapsedSeconds);

        $companySetting = $this->getCompanySetting();

        return view('candidate.runner', compact('assessment', 'submission', 'remainingSeconds', 'companySetting'));
    }

    /**
     * Submit jawaban tes kandidat dan hitung skor DISC.
     */
    public function submit(Request $request, string $slug): RedirectResponse
    {
        $assessment = Assessment::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        $submissionId = session('active_submission_id') ?? $request->input('submission_id');

        $submission = CandidateSubmission::where('id', $submissionId)
            ->where('assessment_id', $assessment->id)
            ->firstOrFail();

        if ($submission->submitted_at) {
            return redirect()->route('candidate.completed', $slug);
        }

        $rawAnswers = $request->input('answers', []);

        // Parsing jika dikirim dalam bentuk JSON string dari form
        if (is_string($rawAnswers)) {
            $rawAnswers = json_decode($rawAnswers, true) ?? [];
        }

        // Cek apakah pengerjaan melebihi durasi batas (+ toleransi 30 detik latency jaringan)
        $durationSeconds = ($assessment->duration_minutes * 60) + 30;
        $elapsedSeconds = Carbon::now()->diffInSeconds($submission->started_at);
        $isTimeOut = ($elapsedSeconds > $durationSeconds) || $request->boolean('is_timeout');

        // Kalkulasi skor DISC
        $discScores = [];
        try {
            if (!empty($rawAnswers)) {
                $discScores = $this->scoringService->calculate($rawAnswers);
            }
        } catch (Exception $e) {
            // Jika ada error pada kalkulasi, simpan pesan error pada skor
            $discScores = [
                'error' => $e->getMessage(),
                'status' => 'incomplete_or_invalid',
            ];
        }

        $submission->update([
            'submitted_at' => now(),
            'is_time_out' => $isTimeOut,
            'answers_payload' => $rawAnswers,
            'disc_scores' => $discScores,
        ]);

        session()->forget('active_submission_id');
        session(['completed_submission_id' => $submission->id]);

        return redirect()->route('candidate.completed', $slug);
    }

    /**
     * Halaman konfirmasi selesai pengerjaan tes.
     */
    public function completed(string $slug): View
    {
        $assessment = Assessment::where('slug', $slug)->firstOrFail();
        $companySetting = $this->getCompanySetting();

        $submissionId = session('completed_submission_id');
        $submission = null;
        if ($submissionId) {
            $submission = CandidateSubmission::with('candidate')->find($submissionId);
        }

        return view('candidate.completed', compact('assessment', 'companySetting', 'submission'));
    }
}
