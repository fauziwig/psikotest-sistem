<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\Candidate;
use App\Models\CandidateSubmission;
use App\Models\CompanySetting;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    /**
     * Tampilkan overview dashboard HR/Admin.
     */
    public function index(): View
    {
        $totalCandidates = Candidate::count();
        $totalSubmissions = CandidateSubmission::whereNotNull('submitted_at')->count();
        $activeAssessments = Assessment::where('is_published', true)->count();

        // 5 submission terbaru
        $recentSubmissions = CandidateSubmission::whereNotNull('submitted_at')
            ->with(['candidate', 'assessment'])
            ->latest('submitted_at')
            ->take(5)
            ->get();

        // Distribusi Profil DISC dari seluruh submission
        $submissions = CandidateSubmission::whereNotNull('submitted_at')->get();
        $discCounts = ['D' => 0, 'I' => 0, 'S' => 0, 'C' => 0];

        foreach ($submissions as $sub) {
            $scores = $sub->disc_scores;
            if (isset($scores['primary_dimension']) && array_key_exists($scores['primary_dimension'], $discCounts)) {
                $discCounts[$scores['primary_dimension']]++;
            }
        }

        $companySetting = CompanySetting::first() ?? new CompanySetting([
            'company_name' => 'Assessment Center',
            'primary_color' => '#2563eb',
            'secondary_color' => '#475569',
        ]);

        return view('admin.dashboard.index', compact(
            'totalCandidates',
            'totalSubmissions',
            'activeAssessments',
            'recentSubmissions',
            'discCounts',
            'companySetting'
        ));
    }
}
