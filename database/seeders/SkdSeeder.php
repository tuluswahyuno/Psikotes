<?php

namespace Database\Seeders;

use App\Models\Test;
use App\Models\Question;
use App\Models\Option;
use App\Models\SkdPackage;
use App\Models\SkdPackageTest;
use Illuminate\Database\Seeder;

class SkdSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create TWK Test (30 questions)
        $twk = Test::create(['title' => 'CAT TWK - Nasionalisme & Integritas', 'category' => 'TWK', 'duration_minutes' => 30, 'type' => 'multiple_choice', 'is_active' => true]);
        $this->seedQuestions($twk, 30, 'twk');

        // 2. Create TIU Test (35 questions)
        $tiu = Test::create(['title' => 'CAT TIU - Kemampuan Analitis & Numerik', 'category' => 'TIU', 'duration_minutes' => 35, 'type' => 'multiple_choice', 'is_active' => true]);
        $this->seedQuestions($tiu, 35, 'tiu');

        // 3. Create TKP Test (45 questions)
        $tkp = Test::create(['title' => 'CAT TKP - Karakteristik Pribadi', 'category' => 'TKP', 'duration_minutes' => 45, 'type' => 'personality', 'is_active' => true]);
        $this->seedQuestions($tkp, 45, 'tkp');

        // 4. Create SKD Package
        $package = SkdPackage::create([
            'title' => 'Simulasi SKD CPNS 2026 - Paket Primadona',
            'description' => 'Paket simulasi lengkap CAT SKD CPNS sesuai Kisi-Kisi Permenpan RB. Terdiri dari total 110 butir soal dengan durasi 100 menit.',
            'duration_minutes' => 100,
            'is_active' => true
        ]);

        SkdPackageTest::create(['skd_package_id' => $package->id, 'test_id' => $twk->id, 'sub_test_type' => 'twk', 'passing_grade' => 65, 'score_per_correct' => 5]);
        SkdPackageTest::create(['skd_package_id' => $package->id, 'test_id' => $tiu->id, 'sub_test_type' => 'tiu', 'passing_grade' => 80, 'score_per_correct' => 5]);
        SkdPackageTest::create(['skd_package_id' => $package->id, 'test_id' => $tkp->id, 'sub_test_type' => 'tkp', 'passing_grade' => 166, 'score_per_correct' => 5]);
    }

    private function seedQuestions($test, $count, $type)
    {
        for ($i = 1; $i <= $count; $i++) {
            $question = Question::create([
                'test_id' => $test->id,
                'question_text' => "Contoh soal {$type} nomor {$i} - Materi ini menguji kompetensi standar SKD CPNS.",
                'type' => 'choice',
                'order' => $i,
                'weight' => 1
            ]);

            if ($type == 'tkp') {
                // TKP graduated scoring 1-5
                for ($j = 5; $j >= 1; $j--) {
                    Option::create([
                        'question_id' => $question->id,
                        'option_text' => "Pilihan Jawaban (Poin {$j})",
                        'is_correct' => ($j == 5),
                        'score' => $j,
                        'order' => 6 - $j
                    ]);
                }
            } else {
                // TWK / TIU fixed scoring (1 correct, 3 wrong)
                for ($j = 1; $j <= 4; $j++) {
                    Option::create([
                        'question_id' => $question->id,
                        'option_text' => ($j == 1) ? "Jawaban Benar (Skor 5)" : "Jawaban Salah (Skor 0)",
                        'is_correct' => ($j == 1),
                        'score' => ($j == 1 ? 5 : 0),
                        'order' => $j
                    ]);
                }
            }
        }
    }
}
