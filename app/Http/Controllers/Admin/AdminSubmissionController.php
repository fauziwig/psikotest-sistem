<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\CandidateSubmission;
use App\Models\CompanySetting;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminSubmissionController extends Controller
{
    /**
     * Tampilkan daftar submission hasil assessment kandidat.
     */
    public function index(Request $request): View
    {
        $query = CandidateSubmission::with(['candidate', 'assessment'])
            ->whereNotNull('submitted_at')
            ->latest('submitted_at');

        // Pencarian Nama, WhatsApp, atau Posisi
        if ($search = $request->input('search')) {
            $query->whereHas('candidate', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('whatsapp_number', 'like', "%{$search}%")
                    ->orWhere('applied_position', 'like', "%{$search}%");
            });
        }

        // Filter Platform Lamaran
        if ($platform = $request->input('platform')) {
            $query->whereHas('candidate', function ($q) use ($platform) {
                $q->where('source_platform', $platform);
            });
        }

        // Filter Dimensi DISC
        if ($disc = $request->input('disc')) {
            $query->whereJsonContains('disc_scores->primary_dimension', strtoupper($disc));
        }

        $submissions = $query->paginate(15)->withQueryString();

        $assessments = Assessment::all();
        $platforms = ['Glints', 'Pintarnya.com', 'Jobstreet', 'LinkedIn', 'KitaLulus', 'Kalibrr', 'Referral / Rekomendasi', 'Website Perusahaan'];

        $companySetting = CompanySetting::first() ?? new CompanySetting();

        return view('admin.submissions.index', compact('submissions', 'assessments', 'platforms', 'companySetting'));
    }

    /**
     * Tampilkan halaman detail hasil kalkulasi DISC kandidat.
     */
    public function show(int $id): View
    {
        $submission = CandidateSubmission::with([
            'candidate',
            'assessment.questions.options',
        ])->findOrFail($id);

        $assessment = $submission->assessment;
        $discScores = $submission->disc_scores ?? [];
        $rawAnswers = $submission->answers_payload ?? [];

        // Buat map jawaban berdasarkan question_number untuk kemudahan render di Blade
        $answersMap = [];
        foreach ($rawAnswers as $ans) {
            if (isset($ans['question_number'])) {
                $answersMap[$ans['question_number']] = $ans;
            }
        }

        $graph1Mask = $discScores['graph_1_mask'] ?? ['D' => 0, 'I' => 0, 'S' => 0, 'C' => 0];
        $graph2Core = $discScores['graph_2_core'] ?? ['D' => 0, 'I' => 0, 'S' => 0, 'C' => 0];
        $graph3Mirror = $discScores['graph_3_mirror'] ?? ['D' => 0, 'I' => 0, 'S' => 0, 'C' => 0];
        $companySetting = CompanySetting::first() ?? new CompanySetting();

        return view('admin.submissions.show', compact(
            'submission',
            'assessment',
            'discScores',
            'answersMap',
            'graph1Mask',
            'graph2Core',
            'graph3Mirror',
            'companySetting'
        ));
    }
}
