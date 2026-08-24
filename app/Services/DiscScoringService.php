<?php

namespace App\Services;

use InvalidArgumentException;

class DiscScoringService
{
    /**
     * Profil nama & deskripsi berdasarkan dimensi DISC dominan.
     */
    protected array $profiles = [
        'D' => [
            'name' => 'Dominance (Driver / Direct)',
            'summary' => 'Berorientasi pada hasil, tegas, kompetitif, menyukai tantangan, dan cepat mengambil keputusan.',
            'strengths' => ['Kepemimpinan tegas', 'Problem solver cepat', 'Fokus pada pencapaian target'],
            'work_environment' => 'Lingkungan dinamis dengan wewenang pengambilan keputusan dan target yang jelas.',
        ],
        'I' => [
            'name' => 'Influence (Influencer / Inspiring)',
            'summary' => 'Antusias, komunikatif, persuasif, ramah, dan pandai membangun relasi sosial serta memotivasi orang lain.',
            'strengths' => ['Komunikasi & persuasi', 'Membangun jejaring', 'Membawa energi positif ke tim'],
            'work_environment' => 'Lingkungan kolaboratif yang mengutamakan interaksi antarmanusia dan kreativitas.',
        ],
        'S' => [
            'name' => 'Steadiness (Supporter / Stable)',
            'summary' => 'Tenang, sabar, dapat diandalkan, setia, pendengar yang baik, dan menghargai kerja sama tim yang harmonis.',
            'strengths' => ['Kerja sama tim yang konsisten', 'Pendengar aktif', 'Tekun dan loyal'],
            'work_environment' => 'Lingkungan kerja yang stabil, terstruktur, suportif, dan minim konflik.',
        ],
        'C' => [
            'name' => 'Conscientiousness (Compliant / Analytical)',
            'summary' => 'Teliti, analitis, sistematis, mengutamakan kualitas, akurasi data, dan kepatuhan terhadap standar serta prosedur.',
            'strengths' => ['Analisis mendalam', 'Akurasi & detail oriented', 'Perencanaan sistematis'],
            'work_environment' => 'Lingkungan terorganisir dengan standar kualitas tinggi dan prosedur operasional yang jelas.',
        ],
    ];

    /**
     * Hitung skor Most, Least, Change, dan analisis profil kepribadian DISC.
     *
     * @param array $answers Array of answer items:
     *   [
     *     [
     *       'question_number' => 1,
     *       'most_option_id' => 1,
     *       'least_option_id' => 4,
     *       'most_disc' => 'D',
     *       'least_disc' => 'C',
     *     ],
     *     ...
     *   ]
     * @return array
     */
    public function calculate(array $answers): array
    {
        if (empty($answers)) {
            throw new InvalidArgumentException('Jawaban tidak boleh kosong.');
        }

        $mostScores = ['D' => 0, 'I' => 0, 'S' => 0, 'C' => 0];
        $leastScores = ['D' => 0, 'I' => 0, 'S' => 0, 'C' => 0];
        $uncompletedQuestions = [];

        foreach ($answers as $index => $answer) {
            $questionNum = $answer['question_number'] ?? ($index + 1);
            $hasMost = false;
            $hasLeast = false;

            // Validasi jika Most dan Least memilih opsi yang sama (dan bukan null)
            if (isset($answer['most_option_id']) && isset($answer['least_option_id']) && $answer['most_option_id'] !== null && $answer['least_option_id'] !== null) {
                if ($answer['most_option_id'] === $answer['least_option_id']) {
                    throw new InvalidArgumentException("Pilihan Most dan Least tidak boleh pada pernyataan yang sama pada nomor {$questionNum}.");
                }
            }

            // Validasi & hitung pilihan Most
            if (!empty($answer['most_disc'])) {
                $mostDisc = strtoupper((string) $answer['most_disc']);
                if (!array_key_exists($mostDisc, $mostScores)) {
                    throw new InvalidArgumentException("Tipe dimensi DISC tidak valid pada soal nomor {$questionNum}.");
                }
                $mostScores[$mostDisc]++;
                $hasMost = true;
            }

            // Validasi & hitung pilihan Least
            if (!empty($answer['least_disc'])) {
                $leastDisc = strtoupper((string) $answer['least_disc']);
                if (!array_key_exists($leastDisc, $leastScores)) {
                    throw new InvalidArgumentException("Tipe dimensi DISC tidak valid pada soal nomor {$questionNum}.");
                }
                $leastScores[$leastDisc]++;
                $hasLeast = true;
            }

            if (!$hasMost || !$hasLeast) {
                $uncompletedQuestions[] = $questionNum;
            }
        }

        // Grafik 3: Change / Mirror (Most minus Least)
        $changeScores = [
            'D' => $mostScores['D'] - $leastScores['D'],
            'I' => $mostScores['I'] - $leastScores['I'],
            'S' => $mostScores['S'] - $leastScores['S'],
            'C' => $mostScores['C'] - $leastScores['C'],
        ];

        // Tentukan pola profil utama berdasarkan Grafik Mirror (Change) atau Mask (Most)
        $dominant = $this->determineDominantProfile($changeScores, $mostScores);

        return [
            'total_questions_answered' => count($answers),
            'is_complete' => empty($uncompletedQuestions),
            'uncompleted_questions' => $uncompletedQuestions,
            'graph_1_mask' => $mostScores,       // Public / Mask Behavior (Most)
            'graph_2_core' => $leastScores,      // Private / Core Behavior (Least)
            'graph_3_mirror' => $changeScores,   // Perceived / Integrated (Most - Least)
            'primary_dimension' => $dominant['primary'],
            'secondary_dimension' => $dominant['secondary'],
            'profile_code' => $dominant['code'],
            'profile_name' => $dominant['name'],
            'summary' => $dominant['summary'],
            'strengths' => $dominant['strengths'],
            'work_environment' => $dominant['work_environment'],
            'calculated_at' => now()->toIso8601String(),
        ];
    }

    /**
     * Tentukan dimensi primer dan sekunder dari skor.
     */
    protected function determineDominantProfile(array $changeScores, array $mostScores): array
    {
        // Urutkan skor change dari yang tertinggi
        arsort($changeScores);
        $keys = array_keys($changeScores);

        $primary = $keys[0];
        $secondary = $keys[1];

        // Jika ada nilai seri pada change, gunakan skor Most sebagai tie breaker
        if ($changeScores[$primary] === $changeScores[$secondary]) {
            if ($mostScores[$secondary] > $mostScores[$primary]) {
                $temp = $primary;
                $primary = $secondary;
                $secondary = $temp;
            }
        }

        $code = $primary . '/' . $secondary;
        $profile = $this->profiles[$primary] ?? $this->profiles['D'];

        return [
            'primary' => $primary,
            'secondary' => $secondary,
            'code' => $code,
            'name' => $profile['name'],
            'summary' => $profile['summary'],
            'strengths' => $profile['strengths'],
            'work_environment' => $profile['work_environment'],
        ];
    }
}
