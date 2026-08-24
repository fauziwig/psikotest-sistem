<?php

namespace Tests\Unit;

use App\Services\DiscScoringService;
use InvalidArgumentException;
use Tests\TestCase;

class DiscScoringServiceTest extends TestCase
{
    protected DiscScoringService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new DiscScoringService();
    }

    public function test_can_calculate_disc_scores_correctly(): void
    {
        // 24 dummy answers
        // Let's create:
        // 12 Most D, 6 Most I, 4 Most S, 2 Most C
        // 2 Least D, 4 Least I, 8 Least S, 10 Least C
        $answers = [];

        // 12 questions: Most D, Least C (for first 10), Least S (for next 2)
        for ($i = 1; $i <= 10; $i++) {
            $answers[] = [
                'question_number' => $i,
                'most_option_id' => 1,
                'least_option_id' => 4,
                'most_disc' => 'D',
                'least_disc' => 'C',
            ];
        }
        for ($i = 11; $i <= 12; $i++) {
            $answers[] = [
                'question_number' => $i,
                'most_option_id' => 1,
                'least_option_id' => 3,
                'most_disc' => 'D',
                'least_disc' => 'S',
            ];
        }

        // 6 questions: Most I, Least S
        for ($i = 13; $i <= 18; $i++) {
            $answers[] = [
                'question_number' => $i,
                'most_option_id' => 2,
                'least_option_id' => 3,
                'most_disc' => 'I',
                'least_disc' => 'S',
            ];
        }

        // 4 questions: Most S, Least D (2) and Least I (2)
        for ($i = 19; $i <= 20; $i++) {
            $answers[] = [
                'question_number' => $i,
                'most_option_id' => 3,
                'least_option_id' => 1,
                'most_disc' => 'S',
                'least_disc' => 'D',
            ];
        }
        for ($i = 21; $i <= 22; $i++) {
            $answers[] = [
                'question_number' => $i,
                'most_option_id' => 3,
                'least_option_id' => 2,
                'most_disc' => 'S',
                'least_disc' => 'I',
            ];
        }

        // 2 questions: Most C, Least I
        for ($i = 23; $i <= 24; $i++) {
            $answers[] = [
                'question_number' => $i,
                'most_option_id' => 4,
                'least_option_id' => 2,
                'most_disc' => 'C',
                'least_disc' => 'I',
            ];
        }

        $result = $this->service->calculate($answers);

        // Verification of Graph 1: Mask (Most)
        $this->assertEquals(12, $result['graph_1_mask']['D']);
        $this->assertEquals(6, $result['graph_1_mask']['I']);
        $this->assertEquals(4, $result['graph_1_mask']['S']);
        $this->assertEquals(2, $result['graph_1_mask']['C']);

        // Verification of Graph 2: Core (Least)
        $this->assertEquals(2, $result['graph_2_core']['D']);
        $this->assertEquals(4, $result['graph_2_core']['I']);
        $this->assertEquals(8, $result['graph_2_core']['S']);
        $this->assertEquals(10, $result['graph_2_core']['C']);

        // Verification of Graph 3: Mirror (Most - Least)
        $this->assertEquals(10, $result['graph_3_mirror']['D']);  // 12 - 2 = 10
        $this->assertEquals(2, $result['graph_3_mirror']['I']);   // 6 - 4 = 2
        $this->assertEquals(-4, $result['graph_3_mirror']['S']);  // 4 - 8 = -4
        $this->assertEquals(-8, $result['graph_3_mirror']['C']);  // 2 - 10 = -8

        // Primary & Secondary Dimensions
        $this->assertEquals('D', $result['primary_dimension']);
        $this->assertEquals('I', $result['secondary_dimension']);
        $this->assertEquals('D/I', $result['profile_code']);
        $this->assertStringContainsString('Dominance', $result['profile_name']);
        $this->assertNotEmpty($result['summary']);
        $this->assertIsArray($result['strengths']);
    }

    public function test_throws_exception_when_most_and_least_are_same_option(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Pilihan Most dan Least tidak boleh pada pernyataan yang sama');

        $this->service->calculate([
            [
                'question_number' => 1,
                'most_option_id' => 5,
                'least_option_id' => 5, // SAME OPTION
                'most_disc' => 'D',
                'least_disc' => 'D',
            ],
        ]);
    }

    public function test_throws_exception_on_empty_answers(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Jawaban tidak boleh kosong.');

        $this->service->calculate([]);
    }

    public function test_throws_exception_on_invalid_disc_dimension(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Tipe dimensi DISC tidak valid');

        $this->service->calculate([
            [
                'question_number' => 1,
                'most_option_id' => 1,
                'least_option_id' => 2,
                'most_disc' => 'X', // Invalid
                'least_disc' => 'D',
            ],
        ]);
    }
}
