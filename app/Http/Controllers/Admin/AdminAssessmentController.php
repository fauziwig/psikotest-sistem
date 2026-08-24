<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\CompanySetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminAssessmentController extends Controller
{
    /**
     * Tampilkan daftar assessment.
     */
    public function index(): View
    {
        $assessments = Assessment::withCount(['questions', 'submissions'])->latest()->get();
        $companySetting = CompanySetting::first() ?? new CompanySetting();

        return view('admin.assessments.index', compact('assessments', 'companySetting'));
    }

    /**
     * Update konfigurasi assessment (durasi, status publish).
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $assessment = Assessment::findOrFail($id);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'duration_minutes' => ['required', 'integer', 'min:1', 'max:180'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $validated['is_published'] = $request->boolean('is_published');

        $assessment->update($validated);

        return back()->with('success', 'Pengaturan assessment berhasil diperbarui.');
    }
}
