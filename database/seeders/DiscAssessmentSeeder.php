<?php

namespace Database\Seeders;

use App\Models\Assessment;
use App\Models\CompanySetting;
use App\Models\Question;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DiscAssessmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Default Company Branding Settings
        CompanySetting::updateOrCreate(
            ['id' => 1],
            [
                'company_name' => 'TalentCorp International',
                'logo_path' => null,
                'favicon_path' => null,
                'primary_color' => '#2563eb', // Blue
                'secondary_color' => '#475569', // Slate
            ]
        );

        // 2. Default HR / Admin User
        User::updateOrCreate(
            ['email' => 'hr@company.com'],
            [
                'name' => 'HR Administrator',
                'password' => Hash::make('password123'),
                'role' => 'hr',
            ]
        );

        // 3. Default DISC Assessment
        $assessment = Assessment::updateOrCreate(
            ['slug' => 'disc-behavioral-assessment'],
            [
                'title' => 'DISC Behavioral Assessment',
                'description' => 'Tes profil perilaku kerja standar dengan format forced-choice (pilih 1 yang Paling Menggambarkan diri Anda [Most] dan 1 yang Paling Tidak Menggambarkan diri Anda [Least]).',
                'duration_minutes' => 15,
                'is_published' => true,
            ]
        );

        // 4. Standard 24 Question Bank
        $questionsData = [
            1 => [
                ['text' => 'Mudah bergaul, ramah, dan menyenangkan', 'disc' => 'I'],
                ['text' => 'Percaya diri, tegas, dan berani mengambil risiko', 'disc' => 'D'],
                ['text' => 'Sabar, tenang, dan pendengar yang baik', 'disc' => 'S'],
                ['text' => 'Teliti, hati-hati, dan menyukai keteraturan', 'disc' => 'C'],
            ],
            2 => [
                ['text' => 'Menyukai fakta, data, dan berpikir logis', 'disc' => 'C'],
                ['text' => 'Siap membantu, setia kawan, dan kooperatif', 'disc' => 'S'],
                ['text' => 'Penuh semangat, optimis, dan persuasif', 'disc' => 'I'],
                ['text' => 'Cepat mengambil keputusan dan pantang menyerah', 'disc' => 'D'],
            ],
            3 => [
                ['text' => 'Mampu memotivasi dan memberi inspirasi', 'disc' => 'I'],
                ['text' => 'Menyukai ketenangan dan menghindari konflik', 'disc' => 'S'],
                ['text' => 'Kritis, analitis, dan mengutamakan ketepatan', 'disc' => 'C'],
                ['text' => 'Kompetitif, fokus pada hasil akhir, dan dominan', 'disc' => 'D'],
            ],
            4 => [
                ['text' => 'Sistematis, terorganisir, dan taat prosedur', 'disc' => 'C'],
                ['text' => 'Suka memimpin dan mengarahkan orang lain', 'disc' => 'D'],
                ['text' => 'Ekspresif, ceria, dan pandai menghidupkan suasana', 'disc' => 'I'],
                ['text' => 'Stabil, konsisten, dan dapat diandalkan', 'disc' => 'S'],
            ],
            5 => [
                ['text' => 'Penuh percaya diri dan suka tantangan baru', 'disc' => 'D'],
                ['text' => 'Disiplin, cermat, dan berstandar tinggi', 'disc' => 'C'],
                ['text' => 'Penuh kehangatan, simpatik, dan toleran', 'disc' => 'S'],
                ['text' => 'Suka bergaul, populer, dan supel', 'disc' => 'I'],
            ],
            6 => [
                ['text' => 'Menghindari perubahan mendadak, cinta kedamaian', 'disc' => 'S'],
                ['text' => 'Menuntut kesempurnaan dan akurasi tinggi', 'disc' => 'C'],
                ['text' => 'Berani bersuara dan berpendirian kuat', 'disc' => 'D'],
                ['text' => 'Pandai meyakinkan orang lain dan antusias', 'disc' => 'I'],
            ],
            7 => [
                ['text' => 'Bersemangat, ceria, dan banyak ide', 'disc' => 'I'],
                ['text' => 'Mandiri, berorientasi tindakan, dan gigih', 'disc' => 'D'],
                ['text' => 'Loyal, dapat dipercaya, dan sabar', 'disc' => 'S'],
                ['text' => 'Berhati-hati dalam bertindak dan analitis', 'disc' => 'C'],
            ],
            8 => [
                ['text' => 'Bekerja sesuai rencana dan taat aturan', 'disc' => 'C'],
                ['text' => 'Mengutamakan keharmonisan dalam kelompok', 'disc' => 'S'],
                ['text' => 'Menyukai interaksi sosial dan pujian', 'disc' => 'I'],
                ['text' => 'Suka mengendalikan situasi dan mengambil inisiatif', 'disc' => 'D'],
            ],
            9 => [
                ['text' => 'Tegas, lugas, dan to the point', 'disc' => 'D'],
                ['text' => 'Rapi, terstruktur, dan berorientasi kualitas', 'disc' => 'C'],
                ['text' => 'Mudah berempati dan suka mendengarkan', 'disc' => 'S'],
                ['text' => 'Menarik, komunikatif, dan penuh pesona', 'disc' => 'I'],
            ],
            10 => [
                ['text' => 'Fleksibel, ramah, dan disenangi banyak orang', 'disc' => 'I'],
                ['text' => 'Pemberani, tidak takut konfrontasi', 'disc' => 'D'],
                ['text' => 'Tekun, tidak mudah panik, dan tenang', 'disc' => 'S'],
                ['text' => 'Objektif, realistis, dan berbasis bukti', 'disc' => 'C'],
            ],
            11 => [
                ['text' => 'Cermat dalam memeriksa detail pekerjaan', 'disc' => 'C'],
                ['text' => 'Fokus pada pencapaian target dan efisiensi', 'disc' => 'D'],
                ['text' => 'Mendukung rekan kerja dan cinta kerja sama', 'disc' => 'S'],
                ['text' => 'Membawa kegembiraan dan optimisme tinggi', 'disc' => 'I'],
            ],
            12 => [
                ['text' => 'Menghargai tradisi, stabil, dan setia', 'disc' => 'S'],
                ['text' => 'Suka berbicara di depan umum dan persuasif', 'disc' => 'I'],
                ['text' => 'Kritis, teliti, dan berpikir mendalam', 'disc' => 'C'],
                ['text' => 'Ambisius, tangguh, dan berani bersaing', 'disc' => 'D'],
            ],
            13 => [
                ['text' => 'Berenergi tinggi, dinamis, dan vokal', 'disc' => 'D'],
                ['text' => 'Rendah hati, tidak suka menonjolkan diri', 'disc' => 'S'],
                ['text' => 'Spontan, periang, dan mudah beradaptasi', 'disc' => 'I'],
                ['text' => 'Sistematis, terukur, dan metodis', 'disc' => 'C'],
            ],
            14 => [
                ['text' => 'Suka merencanakan segala sesuatu secara rinci', 'disc' => 'C'],
                ['text' => 'Cepat bergerak dan segera bertindak', 'disc' => 'D'],
                ['text' => 'Suka berteman dan menjalin relasi baru', 'disc' => 'I'],
                ['text' => 'Penuh pertimbangan dan menjaga kerukunan', 'disc' => 'S'],
            ],
            15 => [
                ['text' => 'Tulus, hangat, dan siap membantu kapan saja', 'disc' => 'S'],
                ['text' => 'Bicara apa adanya, tegas, dan percaya diri', 'disc' => 'D'],
                ['text' => 'Menyukai ketertiban dan presisi teknis', 'disc' => 'C'],
                ['text' => 'Penuh daya pikat, humoris, dan bersemangat', 'disc' => 'I'],
            ],
            16 => [
                ['text' => 'Suka membimbing dengan sabar dan telaten', 'disc' => 'S'],
                ['text' => 'Analitis, skeptis, dan mencari kebenaran fakta', 'disc' => 'C'],
                ['text' => 'Mengambil kendali saat krisis terjadi', 'disc' => 'D'],
                ['text' => 'Menginspirasi orang lain dengan visi positif', 'disc' => 'I'],
            ],
            17 => [
                ['text' => 'Menghargai aturan, standar mutu, dan prosedur', 'disc' => 'C'],
                ['text' => 'Menyukai tantangan berat dan kompetisi', 'disc' => 'D'],
                ['text' => 'Senang berinteraksi dan berbagi cerita', 'disc' => 'I'],
                ['text' => 'Penyabar, penuh pengertian, dan damai', 'disc' => 'S'],
            ],
            18 => [
                ['text' => 'Ramah tamah, terbuka, dan bersahabat', 'disc' => 'I'],
                ['text' => 'Pendirian teguh dan tidak mudah goyah', 'disc' => 'D'],
                ['text' => 'Konsisten, stabil, dan dapat diandalkan', 'disc' => 'S'],
                ['text' => 'Cermat, teliti, dan mengutamakan logika', 'disc' => 'C'],
            ],
            19 => [
                ['text' => 'Bertanggung jawab, patuh, dan menjaga ketenangan', 'disc' => 'S'],
                ['text' => 'Langsung pada inti masalah tanpa basa-basi', 'disc' => 'D'],
                ['text' => 'Kreatif, penuh antusiasme, dan ceria', 'disc' => 'I'],
                ['text' => 'Bekerja dengan standar tinggi dan akurat', 'disc' => 'C'],
            ],
            20 => [
                ['text' => 'Kritis dalam mengevaluasi hasil kerja', 'disc' => 'C'],
                ['text' => 'Mudah memaafkan dan mengutamakan harmoni', 'disc' => 'S'],
                ['text' => 'Percaya diri dan berani mengambil inisiatif', 'disc' => 'D'],
                ['text' => 'Menyenangkan dan pandai menghibur orang lain', 'disc' => 'I'],
            ],
            21 => [
                ['text' => 'Penuh keyakinan dan berjiwa pemimpin', 'disc' => 'D'],
                ['text' => 'Menyenangkan dalam pergaulan dan ekspresif', 'disc' => 'I'],
                ['text' => 'Tenang saat menghadapi tekanan', 'disc' => 'S'],
                ['text' => 'Sistematis, teratur, dan analitis', 'disc' => 'C'],
            ],
            22 => [
                ['text' => 'Suka menganalisis data dan mencari solusi logis', 'disc' => 'C'],
                ['text' => 'Menjadi penengah dan meredam ketegangan', 'disc' => 'S'],
                ['text' => 'Mendorong tim mencapai target maksimal', 'disc' => 'D'],
                ['text' => 'Membangun suasana kerja yang penuh semangat', 'disc' => 'I'],
            ],
            23 => [
                ['text' => 'Spontan, ramah, dan gemar bersosialisasi', 'disc' => 'I'],
                ['text' => 'Gigih, tangguh, dan pantang menyerah', 'disc' => 'D'],
                ['text' => 'Setia, sabar, dan pendukung yang baik', 'disc' => 'S'],
                ['text' => 'Disiplin, berhati-hati, dan taat prosedur', 'disc' => 'C'],
            ],
            24 => [
                ['text' => 'Menjaga kualitas dan kesempurnaan hasil', 'disc' => 'C'],
                ['text' => 'Menjaga keharmonisan dan persahabatan tim', 'disc' => 'S'],
                ['text' => 'Tegas dalam mengarahkan dan memimpin', 'disc' => 'D'],
                ['text' => 'Penuh optimisme dan mampu membakar semangat', 'disc' => 'I'],
            ],
        ];

        foreach ($questionsData as $questionNumber => $options) {
            $question = Question::updateOrCreate(
                [
                    'assessment_id' => $assessment->id,
                    'question_number' => $questionNumber,
                ],
                [
                    'order_index' => $questionNumber,
                ]
            );

            // Clear old options if re-seeding to prevent duplicates
            $question->options()->delete();

            foreach ($options as $index => $optionData) {
                $question->options()->create([
                    'option_text' => $optionData['text'],
                    'disc_type' => $optionData['disc'],
                    'order_index' => $index + 1,
                ]);
            }
        }
    }
}
