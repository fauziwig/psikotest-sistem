<?php

namespace Tests\Unit;

use App\Models\Assessment;
use App\Models\Candidate;
use App\Models\CandidateSubmission;
use App\Models\CompanySetting;
use App\Models\Question;
use App\Models\QuestionOption;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatabaseSchemaTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_company_settings(): void
    {
        $setting = CompanySetting::create([
            'company_name' => 'PT Sukses Mandiri',
            'logo_path' => 'logos/company.png',
            'favicon_path' => 'favicons/favicon.ico',
            'primary_color' => '#3b82f6',
            'secondary_color' => '#64748b',
        ]);

        $this->assertDatabaseHas('company_settings', [
            'company_name' => 'PT Sukses Mandiri',
            'primary_color' => '#3b82f6',
        ]);
    }

    public function test_can_create_assessment_with_questions_and_options(): void
    {
        $assessment = Assessment::create([
            'title' => 'Tes DISC Rekrutmen Batch 1',
            'slug' => 'tes-disc-rekrutmen-batch-1',
            'description' => 'Behavioral assessment 24 nomor',
            'duration_minutes' => 15,
            'is_published' => true,
        ]);

        $question = $assessment->questions()->create([
            'question_number' => 1,
            'order_index' => 1,
        ]);

        $question->options()->createMany([
            ['option_text' => 'Mudah bergaul dan ramah', 'disc_type' => 'I', 'order_index' => 1],
            ['option_text' => 'Percaya diri dan tegas', 'disc_type' => 'D', 'order_index' => 2],
            ['option_text' => 'Sabar dan penuh perhatian', 'disc_type' => 'S', 'order_index' => 3],
            ['option_text' => 'Teliti dan sistematis', 'disc_type' => 'C', 'order_index' => 4],
        ]);

        $this->assertCount(1, $assessment->questions);
        $this->assertCount(4, $question->options);
        $this->assertEquals('I', $question->options->first()->disc_type);
    }

    public function test_can_record_candidate_and_submission(): void
    {
        $assessment = Assessment::create([
            'title' => 'Tes DISC 2026',
            'slug' => 'tes-disc-2026',
            'duration_minutes' => 15,
            'is_published' => true,
        ]);

        $candidate = Candidate::create([
            'name' => 'Budi Santoso',
            'whatsapp_number' => '081234567890',
            'applied_position' => 'Fullstack Developer',
            'source_platform' => 'Glints',
        ]);

        $submission = CandidateSubmission::create([
            'assessment_id' => $assessment->id,
            'candidate_id' => $candidate->id,
            'started_at' => now()->subMinutes(10),
            'submitted_at' => now(),
            'is_time_out' => false,
            'answers_payload' => [
                ['question_number' => 1, 'most_disc' => 'D', 'least_disc' => 'S'],
            ],
            'disc_scores' => [
                'most' => ['D' => 1, 'I' => 0, 'S' => 0, 'C' => 0],
                'least' => ['D' => 0, 'I' => 0, 'S' => 1, 'C' => 0],
                'change' => ['D' => 1, 'I' => 0, 'S' => -1, 'C' => 0],
            ],
        ]);

        $this->assertDatabaseHas('candidates', [
            'name' => 'Budi Santoso',
            'whatsapp_number' => '081234567890',
            'applied_position' => 'Fullstack Developer',
            'source_platform' => 'Glints',
        ]);

        $this->assertDatabaseHas('candidate_submissions', [
            'assessment_id' => $assessment->id,
            'candidate_id' => $candidate->id,
            'is_time_out' => false,
        ]);

        $this->assertEquals('Budi Santoso', $submission->candidate->name);
        $this->assertEquals('Tes DISC 2026', $submission->assessment->title);
        $this->assertIsArray($submission->answers_payload);
        $this->assertIsArray($submission->disc_scores);
    }
}
