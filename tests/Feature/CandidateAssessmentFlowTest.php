<?php

namespace Tests\Feature;

use App\Models\Assessment;
use App\Models\Candidate;
use App\Models\CandidateSubmission;
use App\Models\CompanySetting;
use Database\Seeders\DiscAssessmentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CandidateAssessmentFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DiscAssessmentSeeder::class);
    }

    public function test_root_url_redirects_to_published_assessment(): void
    {
        $response = $this->get('/');
        $response->assertRedirect(route('candidate.register', 'disc-behavioral-assessment'));
    }

    public function test_can_view_candidate_registration_page(): void
    {
        $response = $this->get(route('candidate.register', 'disc-behavioral-assessment'));

        $response->assertStatus(200);
        $response->assertSee('TalentCorp International');
        $response->assertSee('DISC Behavioral Assessment');
        $response->assertSee('Nomor WhatsApp');
        $response->assertSee('Posisi yang Dilamar');
        $response->assertSee('Platform Lamaran Kerja');
    }

    public function test_returns_404_for_unpublished_assessment(): void
    {
        $unpublished = Assessment::create([
            'title' => 'Draft Assessment',
            'slug' => 'draft-assessment',
            'duration_minutes' => 15,
            'is_published' => false,
        ]);

        $response = $this->get(route('candidate.register', 'draft-assessment'));
        $response->assertStatus(404);
    }

    public function test_validates_required_candidate_fields(): void
    {
        $response = $this->post(route('candidate.start', 'disc-behavioral-assessment'), []);

        $response->assertSessionHasErrors([
            'name',
            'whatsapp_number',
            'applied_position',
            'source_platform',
        ]);
    }

    public function test_can_start_assessment_and_redirect_to_runner(): void
    {
        $response = $this->post(route('candidate.start', 'disc-behavioral-assessment'), [
            'name' => 'Ahmad Fauzi',
            'whatsapp_number' => '089876543210',
            'applied_position' => 'Senior Backend Engineer',
            'source_platform' => 'Pintarnya.com',
        ]);

        $this->assertDatabaseHas('candidates', [
            'name' => 'Ahmad Fauzi',
            'whatsapp_number' => '089876543210',
            'applied_position' => 'Senior Backend Engineer',
            'source_platform' => 'Pintarnya.com',
        ]);

        $candidate = Candidate::where('whatsapp_number', '089876543210')->first();
        $this->assertNotNull($candidate);

        $submission = CandidateSubmission::where('candidate_id', $candidate->id)->first();
        $this->assertNotNull($submission);
        $this->assertNotNull($submission->started_at);

        $response->assertRedirect(route('candidate.take', 'disc-behavioral-assessment'));
    }

    public function test_can_access_runner_page_with_active_session(): void
    {
        $candidate = Candidate::create([
            'name' => 'Siti Rahma',
            'whatsapp_number' => '081299887766',
            'applied_position' => 'HR Specialist',
            'source_platform' => 'LinkedIn',
        ]);

        $assessment = Assessment::where('slug', 'disc-behavioral-assessment')->first();

        $submission = CandidateSubmission::create([
            'assessment_id' => $assessment->id,
            'candidate_id' => $candidate->id,
            'started_at' => now(),
            'answers_payload' => [],
            'disc_scores' => [],
        ]);

        $response = $this->withSession(['active_submission_id' => $submission->id])
            ->get(route('candidate.take', 'disc-behavioral-assessment'));

        $response->assertStatus(200);
        $response->assertSee('Siti Rahma');
        $response->assertSee('Pilih 1 Most (M) dan 1 Least (L)');
    }

    public function test_can_submit_answers_and_calculate_disc_scores(): void
    {
        $candidate = Candidate::create([
            'name' => 'Rian Hidayat',
            'whatsapp_number' => '085512345678',
            'applied_position' => 'Product Manager',
            'source_platform' => 'Glints',
        ]);

        $assessment = Assessment::where('slug', 'disc-behavioral-assessment')->with('questions.options')->first();

        $submission = CandidateSubmission::create([
            'assessment_id' => $assessment->id,
            'candidate_id' => $candidate->id,
            'started_at' => now()->subMinutes(5),
            'answers_payload' => [],
            'disc_scores' => [],
        ]);

        // Generate answers for all 24 questions
        $answers = [];
        foreach ($assessment->questions as $q) {
            $options = $q->options;
            $answers[] = [
                'question_number' => $q->question_number,
                'most_option_id' => $options[0]->id,
                'least_option_id' => $options[1]->id,
                'most_disc' => $options[0]->disc_type,
                'least_disc' => $options[1]->disc_type,
            ];
        }

        $response = $this->withSession(['active_submission_id' => $submission->id])
            ->post(route('candidate.submit', 'disc-behavioral-assessment'), [
                'answers' => json_encode($answers),
            ]);

        $response->assertRedirect(route('candidate.completed', 'disc-behavioral-assessment'));

        $submission->refresh();
        $this->assertNotNull($submission->submitted_at);
        $this->assertFalse($submission->is_time_out);
        $this->assertNotEmpty($submission->answers_payload);
        $this->assertNotEmpty($submission->disc_scores);
        $this->assertArrayHasKey('graph_1_mask', $submission->disc_scores);
        $this->assertArrayHasKey('graph_2_core', $submission->disc_scores);
        $this->assertArrayHasKey('graph_3_mirror', $submission->disc_scores);
        $this->assertArrayHasKey('profile_name', $submission->disc_scores);
    }
}
