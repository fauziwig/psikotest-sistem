<?php

namespace Tests\Feature;

use App\Models\Assessment;
use App\Models\Candidate;
use App\Models\CandidateSubmission;
use App\Models\CompanySetting;
use App\Models\User;
use Database\Seeders\DiscAssessmentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DiscAssessmentSeeder::class);
        $this->admin = User::where('email', 'hr@company.com')->first();
    }

    public function test_unauthenticated_user_is_redirected_to_login(): void
    {
        $response = $this->get(route('admin.dashboard'));
        $response->assertRedirect(route('admin.login'));
    }

    public function test_can_view_login_page(): void
    {
        $response = $this->get(route('admin.login'));
        $response->assertStatus(200);
        $response->assertSee('Masuk ke Dashboard');
    }

    public function test_can_login_with_valid_credentials(): void
    {
        $response = $this->post(route('admin.login.submit'), [
            'email' => 'hr@company.com',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($this->admin);
        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_cannot_login_with_invalid_credentials(): void
    {
        $response = $this->post(route('admin.login.submit'), [
            'email' => 'hr@company.com',
            'password' => 'wrongpassword',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }

    public function test_authenticated_user_can_view_dashboard_overview(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Dashboard Overview');
        $response->assertSee('Total Kandidat');
        $response->assertSee('Distribusi Tipe Kepribadian DISC Kandidat');
    }

    public function test_can_view_and_filter_submissions_list(): void
    {
        $candidate = Candidate::create([
            'name' => 'Dewi Lestari',
            'whatsapp_number' => '081234567800',
            'applied_position' => 'UI/UX Designer',
            'source_platform' => 'Pintarnya.com',
        ]);

        $assessment = Assessment::first();

        CandidateSubmission::create([
            'assessment_id' => $assessment->id,
            'candidate_id' => $candidate->id,
            'started_at' => now()->subMinutes(10),
            'submitted_at' => now(),
            'is_time_out' => false,
            'answers_payload' => [],
            'disc_scores' => [
                'primary_dimension' => 'I',
                'secondary_dimension' => 'S',
                'profile_code' => 'I/S',
                'profile_name' => 'Influence - Inspiring',
            ],
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.submissions.index', ['search' => 'Dewi']));

        $response->assertStatus(200);
        $response->assertSee('Dewi Lestari');
        $response->assertSee('UI/UX Designer');
        $response->assertSee('Pintarnya.com');
        $response->assertSee('I/S');
    }

    public function test_can_view_submission_detail_with_3_disc_charts(): void
    {
        $candidate = Candidate::create([
            'name' => 'Bambang Pamungkas',
            'whatsapp_number' => '087766554433',
            'applied_position' => 'Account Executive',
            'source_platform' => 'Glints',
        ]);

        $assessment = Assessment::with('questions.options')->first();

        $submission = CandidateSubmission::create([
            'assessment_id' => $assessment->id,
            'candidate_id' => $candidate->id,
            'started_at' => now()->subMinutes(12),
            'submitted_at' => now(),
            'is_time_out' => false,
            'answers_payload' => [
                [
                    'question_number' => 1,
                    'most_option_id' => $assessment->questions->first()->options->first()->id,
                    'least_option_id' => $assessment->questions->first()->options->last()->id,
                    'most_disc' => 'D',
                    'least_disc' => 'C',
                ],
            ],
            'disc_scores' => [
                'graph_1_mask' => ['D' => 10, 'I' => 6, 'S' => 4, 'C' => 4],
                'graph_2_core' => ['D' => 2, 'I' => 4, 'S' => 8, 'C' => 10],
                'graph_3_mirror' => ['D' => 8, 'I' => 2, 'S' => -4, 'C' => -6],
                'primary_dimension' => 'D',
                'secondary_dimension' => 'I',
                'profile_code' => 'D/I',
                'profile_name' => 'Dominance (Driver / Direct)',
                'summary' => 'Berorientasi pada hasil dan cepat mengambil keputusan.',
                'strengths' => ['Kepemimpinan tegas', 'Problem solver'],
                'work_environment' => 'Lingkungan dinamis dengan target jelas.',
            ],
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.submissions.show', $submission->id));

        $response->assertStatus(200);
        $response->assertSee('Bambang Pamungkas');
        $response->assertSee('Account Executive');
        $response->assertSee('Dominance (Driver / Direct)');
        $response->assertSee('Grafik 1: Mask (Most)');
        $response->assertSee('Grafik 2: Core (Least)');
        $response->assertSee('Grafik 3: Mirror (Perceived)');
        $response->assertSee('Rincian Jawaban Soal (1 - 24)');
    }

    public function test_can_update_assessment_settings(): void
    {
        $assessment = Assessment::first();

        $response = $this->actingAs($this->admin)->put(route('admin.assessments.update', $assessment->id), [
            'title' => 'DISC Behavioral Test Updated',
            'description' => 'Deskripsi baru',
            'duration_minutes' => 20,
            'is_published' => 1,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('assessments', [
            'id' => $assessment->id,
            'title' => 'DISC Behavioral Test Updated',
            'duration_minutes' => 20,
            'is_published' => true,
        ]);
    }

    public function test_can_update_company_branding_settings(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('custom-logo.png', 100, 'image/png');

        $response = $this->actingAs($this->admin)->post(route('admin.branding.update'), [
            'company_name' => 'PT Maju Bersama Digital',
            'primary_color' => '#10b981',
            'secondary_color' => '#334155',
            'logo' => $file,
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('company_settings', [
            'company_name' => 'PT Maju Bersama Digital',
            'primary_color' => '#10b981',
            'secondary_color' => '#334155',
        ]);

        $setting = CompanySetting::first();
        $this->assertNotNull($setting->logo_path);
    }

    public function test_can_logout(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.logout'));

        $this->assertGuest();
        $response->assertRedirect(route('admin.login'));
    }
}
