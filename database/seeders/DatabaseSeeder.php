<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Test;
use App\Models\Question;
use App\Models\Option;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@psikotes.test',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // Create sample peserta
        for ($i = 1; $i <= 5; $i++) {
            User::create([
                'name' => "Peserta {$i}",
                'email' => "peserta{$i}@psikotes.test",
                'password' => bcrypt('password'),
                'role' => 'peserta',
            ]);
        }

        // Create sample test: Tes Logika Dasar
        $test = Test::create([
            'title' => 'Tes Logika Dasar',
            'category' => 'Logika',
            'description' => 'Tes ini mengukur kemampuan logika dasar dan penalaran analitis. Terdiri dari 5 soal pilihan ganda.',
            'duration_minutes' => 30,
            'type' => 'multiple_choice',
            'is_active' => true,
        ]);

        $questions = [
            [
                'question_text' => 'Jika semua kucing adalah hewan, dan semua hewan membutuhkan air, maka...',
                'options' => [
                    ['option_text' => 'Semua kucing membutuhkan air', 'is_correct' => true, 'score' => 1],
                    ['option_text' => 'Tidak semua kucing membutuhkan air', 'is_correct' => false, 'score' => 0],
                    ['option_text' => 'Kucing bukan hewan', 'is_correct' => false, 'score' => 0],
                    ['option_text' => 'Air tidak dibutuhkan hewan', 'is_correct' => false, 'score' => 0],
                ],
            ],
            [
                'question_text' => 'Lanjutkan deret berikut: 2, 6, 12, 20, ...',
                'options' => [
                    ['option_text' => '28', 'is_correct' => false, 'score' => 0],
                    ['option_text' => '30', 'is_correct' => true, 'score' => 1],
                    ['option_text' => '32', 'is_correct' => false, 'score' => 0],
                    ['option_text' => '24', 'is_correct' => false, 'score' => 0],
                ],
            ],
            [
                'question_text' => 'Jika A > B, B > C, dan C > D, manakah yang paling besar?',
                'options' => [
                    ['option_text' => 'D', 'is_correct' => false, 'score' => 0],
                    ['option_text' => 'C', 'is_correct' => false, 'score' => 0],
                    ['option_text' => 'B', 'is_correct' => false, 'score' => 0],
                    ['option_text' => 'A', 'is_correct' => true, 'score' => 1],
                ],
            ],
            [
                'question_text' => 'Antonim dari kata "ABSTRAK" adalah...',
                'options' => [
                    ['option_text' => 'Nyata', 'is_correct' => true, 'score' => 1],
                    ['option_text' => 'Samar', 'is_correct' => false, 'score' => 0],
                    ['option_text' => 'Kabur', 'is_correct' => false, 'score' => 0],
                    ['option_text' => 'Rumit', 'is_correct' => false, 'score' => 0],
                ],
            ],
            [
                'question_text' => 'Sebuah toko memberikan diskon 20%. Jika harga asli Rp 150.000, berapa yang harus dibayar?',
                'options' => [
                    ['option_text' => 'Rp 100.000', 'is_correct' => false, 'score' => 0],
                    ['option_text' => 'Rp 130.000', 'is_correct' => false, 'score' => 0],
                    ['option_text' => 'Rp 120.000', 'is_correct' => true, 'score' => 1],
                    ['option_text' => 'Rp 110.000', 'is_correct' => false, 'score' => 0],
                ],
            ],
        ];

        foreach ($questions as $i => $qData) {
            $question = Question::create([
                'test_id' => $test->id,
                'question_text' => $qData['question_text'],
                'type' => 'choice',
                'order' => $i + 1,
                'weight' => 1,
            ]);

            foreach ($qData['options'] as $j => $optData) {
                Option::create([
                    'question_id' => $question->id,
                    'option_text' => $optData['option_text'],
                    'score' => $optData['score'],
                    'is_correct' => $optData['is_correct'],
                    'order' => $j + 1,
                ]);
            }
        }
    }
}
