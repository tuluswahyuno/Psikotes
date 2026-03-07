<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Section;
use App\Models\SubTopic;
use App\Models\Material;
use App\Models\PracticeQuestion;

class LearningSeeder extends Seeder
{
    public function run(): void
    {
        // ===== TWK =====
        $twk = Section::create([
            'name' => 'TWK',
            'slug' => 'twk',
            'description' => 'Tes Wawasan Kebangsaan – mengukur penguasaan pengetahuan dan kemampuan mengimplementasikan nilai-nilai 4 pilar kebangsaan Indonesia.',
            'icon' => 'account_balance',
            'color' => 'blue',
        ]);

        $twkTopics = [
            ['title' => 'Pancasila', 'slug' => 'pancasila', 'description' => 'Pemahaman nilai-nilai Pancasila sebagai dasar negara dan pandangan hidup bangsa.', 'order' => 1],
            ['title' => 'UUD 1945', 'slug' => 'uud-1945', 'description' => 'Pengetahuan tentang konstitusi negara Republik Indonesia.', 'order' => 2],
            ['title' => 'NKRI & Bhinneka Tunggal Ika', 'slug' => 'nkri-bhineka', 'description' => 'Konsep negara kesatuan dan semangat persatuan dalam keberagaman.', 'order' => 3],
            ['title' => 'Sejarah Nasional', 'slug' => 'sejarah-nasional', 'description' => 'Peristiwa-peristiwa penting dalam sejarah perjuangan bangsa Indonesia.', 'order' => 4],
            ['title' => 'Bahasa Indonesia', 'slug' => 'bahasa-indonesia', 'description' => 'Kemampuan berbahasa Indonesia yang baik dan benar dalam konteks kedinasan.', 'order' => 5],
        ];

        foreach ($twkTopics as $topic) {
            $sub = $twk->subTopics()->create($topic);
            $this->seedMaterials($sub, 'twk');
            $this->seedQuestions($sub, 'twk');
        }

        // ===== TIU =====
        $tiu = Section::create([
            'name' => 'TIU',
            'slug' => 'tiu',
            'description' => 'Tes Intelegensi Umum – mengukur kemampuan verbal, numerik, logika, dan analisis figural.',
            'icon' => 'psychology',
            'color' => 'purple',
        ]);

        $tiuTopics = [
            ['title' => 'Verbal / Analogi', 'slug' => 'verbal-analogi', 'description' => 'Kemampuan memahami hubungan antar kata, sinonim, antonim, dan analogi.', 'order' => 1],
            ['title' => 'Numerik / Aritmatika', 'slug' => 'numerik-aritmatika', 'description' => 'Kemampuan berhitung, deret angka, dan operasi matematika dasar.', 'order' => 2],
            ['title' => 'Logika / Penalaran', 'slug' => 'logika-penalaran', 'description' => 'Kemampuan berpikir logis, silogisme, dan penalaran analitis.', 'order' => 3],
            ['title' => 'Figural / Pola Gambar', 'slug' => 'figural-pola', 'description' => 'Kemampuan mengenali pola dan hubungan dalam bentuk gambar atau figur.', 'order' => 4],
        ];

        foreach ($tiuTopics as $topic) {
            $sub = $tiu->subTopics()->create($topic);
            $this->seedMaterials($sub, 'tiu');
            $this->seedQuestions($sub, 'tiu');
        }

        // ===== TKP =====
        $tkp = Section::create([
            'name' => 'TKP',
            'slug' => 'tkp',
            'description' => 'Tes Karakteristik Pribadi – mengukur sikap dan perilaku yang diperlukan dalam melaksanakan tugas sebagai ASN.',
            'icon' => 'diversity_3',
            'color' => 'emerald',
        ]);

        $tkpTopics = [
            ['title' => 'Pelayanan Publik', 'slug' => 'pelayanan-publik', 'description' => 'Sikap dan perilaku dalam memberikan pelayanan terbaik kepada masyarakat.', 'order' => 1],
            ['title' => 'Jejaring Kerja', 'slug' => 'jejaring-kerja', 'description' => 'Kemampuan membangun dan memelihara hubungan kerja yang produktif.', 'order' => 2],
            ['title' => 'Sosial Budaya', 'slug' => 'sosial-budaya', 'description' => 'Kepekaan terhadap keberagaman sosial dan budaya masyarakat Indonesia.', 'order' => 3],
            ['title' => 'Profesionalisme', 'slug' => 'profesionalisme', 'description' => 'Sikap kerja yang profesional, integritas, dan tanggung jawab.', 'order' => 4],
            ['title' => 'Anti-Radikalisme & Integritas', 'slug' => 'anti-radikalisme', 'description' => 'Penolakan terhadap paham radikal dan komitmen terhadap integritas.', 'order' => 5],
        ];

        foreach ($tkpTopics as $topic) {
            $sub = $tkp->subTopics()->create($topic);
            $this->seedMaterials($sub, 'tkp');
            $this->seedQuestions($sub, 'tkp');
        }
    }

    private function seedMaterials(SubTopic $subTopic, string $section): void
    {
        $materialsMap = [
            'twk' => [
                'pancasila' => [
                    ['title' => 'Sejarah Lahirnya Pancasila', 'content' => "<h2>Sejarah Lahirnya Pancasila</h2>\n<p>Pancasila sebagai dasar negara Indonesia lahir melalui proses panjang yang dimulai dari sidang BPUPK (Badan Penyelidik Usaha-usaha Persiapan Kemerdekaan) pada tahun 1945.</p>\n\n<h3>Poin-poin Penting:</h3>\n<ul>\n<li><strong>1 Juni 1945</strong> – Ir. Soekarno menyampaikan gagasan mengenai dasar negara dalam sidang BPUPK yang kemudian diberi nama \"Pancasila\".</li>\n<li><strong>22 Juni 1945</strong> – Piagam Jakarta disusun oleh Panitia Sembilan sebagai rumusan awal dasar negara.</li>\n<li><strong>18 Agustus 1945</strong> – PPKI mengesahkan UUD 1945 dan Pancasila sebagai dasar negara dengan perubahan pada sila pertama.</li>\n</ul>\n\n<h3>Lima Sila Pancasila:</h3>\n<ol>\n<li>Ketuhanan Yang Maha Esa</li>\n<li>Kemanusiaan yang Adil dan Beradab</li>\n<li>Persatuan Indonesia</li>\n<li>Kerakyatan yang Dipimpin oleh Hikmat Kebijaksanaan dalam Permusyawaratan/Perwakilan</li>\n<li>Keadilan Sosial bagi Seluruh Rakyat Indonesia</li>\n</ol>"],
                    ['title' => 'Nilai-nilai Pancasila dalam Kehidupan', 'content' => "<h2>Implementasi Nilai Pancasila</h2>\n<p>Setiap sila Pancasila memiliki nilai-nilai yang dapat diimplementasikan dalam kehidupan sehari-hari maupun dalam konteks kedinasan sebagai ASN.</p>\n\n<h3>Sila 1 – Ketuhanan:</h3>\n<p>Menghormati kebebasan beragama, toleransi antar umat beragama.</p>\n\n<h3>Sila 2 – Kemanusiaan:</h3>\n<p>Menjunjung tinggi HAM, menghargai harkat dan martabat manusia.</p>\n\n<h3>Sila 3 – Persatuan:</h3>\n<p>Mengutamakan kepentingan bangsa di atas kepentingan golongan.</p>\n\n<h3>Sila 4 – Kerakyatan:</h3>\n<p>Musyawarah untuk mufakat, menghargai pendapat orang lain.</p>\n\n<h3>Sila 5 – Keadilan:</h3>\n<p>Pemerataan kesejahteraan, tidak diskriminatif dalam pelayanan publik.</p>"],
                ],
                'uud-1945' => [
                    ['title' => 'Struktur UUD 1945', 'content' => "<h2>Struktur UUD 1945</h2>\n<p>UUD 1945 sebagai konstitusi tertulis tertinggi Indonesia terdiri dari beberapa bagian utama.</p>\n\n<h3>Bagian-bagian UUD 1945:</h3>\n<ul>\n<li><strong>Pembukaan (Preambule)</strong> – 4 alinea, memuat tujuan nasional dan dasar negara</li>\n<li><strong>Batang Tubuh</strong> – 37 Pasal (setelah amandemen: 21 Bab, 73 Pasal)</li>\n<li><strong>Aturan Peralihan</strong> – Ketentuan transisi</li>\n<li><strong>Aturan Tambahan</strong> – Ketentuan pelengkap</li>\n</ul>\n\n<h3>Amandemen UUD 1945:</h3>\n<p>UUD 1945 telah mengalami 4 kali amandemen (1999, 2000, 2001, 2002) untuk menyempurnakan sistem ketatanegaraan Indonesia.</p>"],
                ],
                'nkri-bhineka' => [
                    ['title' => 'Konsep NKRI', 'content' => "<h2>Negara Kesatuan Republik Indonesia</h2>\n<p>NKRI adalah bentuk negara yang tidak terbagi-bagi, kedaulatan negara dijalankan oleh pemerintah pusat.</p>\n\n<h3>Prinsip NKRI:</h3>\n<ul>\n<li>Wilayah NKRI dari Sabang sampai Merauke</li>\n<li>Satu pemerintahan pusat yang berdaulat</li>\n<li>Otonomi daerah dalam kerangka NKRI</li>\n<li>Keutuhan wilayah tidak dapat diganggu gugat</li>\n</ul>\n\n<h3>Bhinneka Tunggal Ika:</h3>\n<p>Meskipun berbeda-beda tetapi tetap satu. Semboyan ini mencerminkan keberagaman suku, agama, ras, dan budaya yang bersatu dalam wadah NKRI.</p>"],
                ],
                'sejarah-nasional' => [
                    ['title' => 'Perjuangan Kemerdekaan', 'content' => "<h2>Sejarah Perjuangan Kemerdekaan</h2>\n<p>Perjuangan bangsa Indonesia untuk mencapai kemerdekaan melalui berbagai fase penting.</p>\n\n<h3>Tonggak Sejarah:</h3>\n<ul>\n<li><strong>20 Mei 1908</strong> – Hari Kebangkitan Nasional (Budi Utomo)</li>\n<li><strong>28 Oktober 1928</strong> – Sumpah Pemuda</li>\n<li><strong>17 Agustus 1945</strong> – Proklamasi Kemerdekaan</li>\n</ul>"],
                ],
                'bahasa-indonesia' => [
                    ['title' => 'Tata Bahasa dalam Kedinasan', 'content' => "<h2>Bahasa Indonesia dalam Konteks Kedinasan</h2>\n<p>Sebagai ASN, kemampuan menggunakan bahasa Indonesia yang baik dan benar sangat diperlukan dalam proses administrasi dan komunikasi resmi.</p>\n\n<h3>Aspek yang Diuji:</h3>\n<ul>\n<li>Ejaan Yang Disempurnakan (EYD/PUEBI)</li>\n<li>Pemilihan kata (diksi) yang tepat</li>\n<li>Penyusunan kalimat efektif</li>\n<li>Pemahaman bacaan dan paragraf</li>\n</ul>"],
                ],
            ],
            'tiu' => [
                'verbal-analogi' => [
                    ['title' => 'Teknik Menjawab Soal Analogi', 'content' => "<h2>Tes Verbal & Analogi</h2>\n<p>Tes verbal mengukur kemampuan memahami hubungan kata dan makna dalam bahasa Indonesia.</p>\n\n<h3>Jenis Soal:</h3>\n<ul>\n<li><strong>Sinonim</strong> – Mencari kata yang bermakna sama</li>\n<li><strong>Antonim</strong> – Mencari kata yang bermakna berlawanan</li>\n<li><strong>Analogi</strong> – Mencari hubungan antar pasangan kata</li>\n</ul>\n\n<h3>Tips:</h3>\n<p>Pahami konteks kalimat, jangan hanya melihat definisi kamus. Perhatikan hubungan logis antar kata dalam analogi (sebab-akibat, bagian-keseluruhan, dll).</p>"],
                ],
                'numerik-aritmatika' => [
                    ['title' => 'Rumus dan Pola Deret Angka', 'content' => "<h2>Tes Numerik & Aritmatika</h2>\n<p>Mengukur kemampuan berhitung dan mengenali pola bilangan.</p>\n\n<h3>Topik Utama:</h3>\n<ul>\n<li><strong>Deret angka</strong> – Aritmatika, geometri, Fibonacci, campuran</li>\n<li><strong>Operasi hitung</strong> – Persen, perbandingan, rata-rata</li>\n<li><strong>Soal cerita</strong> – Jarak-waktu-kecepatan, untung-rugi</li>\n</ul>\n\n<h3>Contoh Deret:</h3>\n<p>2, 4, 8, 16, 32, ? → Jawaban: 64 (deret geometri, rasio = 2)</p>"],
                ],
                'logika-penalaran' => [
                    ['title' => 'Silogisme dan Penalaran', 'content' => "<h2>Tes Logika & Penalaran</h2>\n<p>Mengukur kemampuan berpikir logis dan menarik kesimpulan dari premis yang diberikan.</p>\n\n<h3>Jenis Soal:</h3>\n<ul>\n<li><strong>Silogisme</strong> – Menarik kesimpulan dari dua premis</li>\n<li><strong>Penalaran analitis</strong> – Menganalisis kondisi untuk menemukan jawaban</li>\n<li><strong>Logika proposisi</strong> – Benar/salah berdasarkan aturan logika</li>\n</ul>\n\n<h3>Contoh Silogisme:</h3>\n<p>Semua PNS adalah ASN. Budi adalah PNS. Kesimpulan: Budi adalah ASN ✅</p>"],
                ],
                'figural-pola' => [
                    ['title' => 'Mengenali Pola Figural', 'content' => "<h2>Tes Figural / Pola Gambar</h2>\n<p>Mengukur kemampuan mengenali pola, perputaran, dan transformasi bentuk.</p>\n\n<h3>Teknik Penyelesaian:</h3>\n<ul>\n<li>Perhatikan rotasi dan refleksi gambar</li>\n<li>Cari pola penambahan/pengurangan elemen</li>\n<li>Identifikasi perubahan warna/arsiran</li>\n<li>Gunakan eliminasi untuk pilihan yang tidak cocok</li>\n</ul>"],
                ],
            ],
            'tkp' => [
                'pelayanan-publik' => [
                    ['title' => 'Prinsip Pelayanan Publik ASN', 'content' => "<h2>Pelayanan Publik</h2>\n<p>Sebagai ASN, pelayanan publik merupakan tugas utama yang harus dijalankan dengan prinsip-prinsip tertentu.</p>\n\n<h3>Prinsip Utama:</h3>\n<ul>\n<li><strong>Responsif</strong> – Cepat tanggap terhadap kebutuhan masyarakat</li>\n<li><strong>Transparan</strong> – Terbuka dan jelas dalam prosedur</li>\n<li><strong>Akuntabel</strong> – Bertanggung jawab atas setiap tindakan</li>\n<li><strong>Adil</strong> – Tidak diskriminatif dalam pelayanan</li>\n</ul>\n\n<h3>Tips Menjawab Soal TKP:</h3>\n<p>Pilih jawaban yang paling menunjukkan sikap proaktif, empati, dan berorientasi pelayanan. Jangan memilih jawaban yang bersifat pasif atau mengabaikan kebutuhan masyarakat.</p>"],
                ],
                'jejaring-kerja' => [
                    ['title' => 'Membangun Hubungan Kerja Profesional', 'content' => "<h2>Jejaring Kerja</h2>\n<p>Kemampuan membangun dan memelihara jaringan kerja yang efektif sebagai ASN.</p>\n\n<h3>Kompetensi yang Diukur:</h3>\n<ul>\n<li>Kemampuan bekerjasama dalam tim</li>\n<li>Komunikasi efektif dengan rekan kerja</li>\n<li>Mengelola konflik secara profesional</li>\n<li>Membangun hubungan lintas instansi</li>\n</ul>"],
                ],
                'sosial-budaya' => [
                    ['title' => 'Kepekaan Sosial & Budaya', 'content' => "<h2>Sosial Budaya</h2>\n<p>Mengukur kepekaan terhadap kondisi sosial dan kemampuan berinteraksi dalam keberagaman budaya.</p>\n\n<h3>Aspek yang Diukur:</h3>\n<ul>\n<li>Toleransi terhadap perbedaan</li>\n<li>Empati terhadap kondisi masyarakat</li>\n<li>Penghargaan terhadap kearifan lokal</li>\n<li>Adaptasi dalam lingkungan multikultural</li>\n</ul>"],
                ],
                'profesionalisme' => [
                    ['title' => 'Etika Kerja ASN', 'content' => "<h2>Profesionalisme ASN</h2>\n<p>Standar perilaku dan etika kerja yang harus dimiliki seorang ASN profesional.</p>\n\n<h3>Indikator Profesionalisme:</h3>\n<ul>\n<li><strong>Disiplin</strong> – Mematuhi aturan dan ketentuan</li>\n<li><strong>Kompeten</strong> – Menguasai bidang tugas</li>\n<li><strong>Akuntabel</strong> – Bertanggung jawab atas pekerjaan</li>\n<li><strong>Harmonis</strong> – Menjaga keselarasan lingkungan kerja</li>\n<li><strong>Loyal</strong> – Setia pada Pancasila dan UUD 1945</li>\n<li><strong>Adaptif</strong> – Cepat menyesuaikan dengan perubahan</li>\n<li><strong>Kolaboratif</strong> – Mampu bekerja sama</li>\n</ul>\n<p>Nilai-nilai di atas dikenal sebagai <strong>BerAKHLAK</strong>.</p>"],
                ],
                'anti-radikalisme' => [
                    ['title' => 'Anti-Radikalisme dan Integritas', 'content' => "<h2>Anti-Radikalisme & Integritas</h2>\n<p>ASN wajib menolak segala bentuk radikalisme dan menjunjung tinggi integritas.</p>\n\n<h3>Aspek yang Diukur:</h3>\n<ul>\n<li>Penolakan terhadap paham yang bertentangan dengan Pancasila</li>\n<li>Komitmen terhadap keutuhan NKRI</li>\n<li>Kejujuran dan konsistensi moral</li>\n<li>Tidak korupsi dan menyalahgunakan kewenangan</li>\n</ul>"],
                ],
            ],
        ];

        $slug = $subTopic->slug;
        $matData = $materialsMap[$section][$slug] ?? [];
        foreach ($matData as $i => $mat) {
            Material::create([
                'sub_topic_id' => $subTopic->id,
                'title' => $mat['title'],
                'content' => $mat['content'],
                'order' => $i + 1,
            ]);
        }
    }

    private function seedQuestions(SubTopic $subTopic, string $section): void
    {
        $questionsMap = [
            'twk' => [
                'pancasila' => [
                    ['question' => 'Siapakah yang pertama kali mengusulkan nama "Pancasila" sebagai dasar negara?', 'options' => ['A' => 'Mohammad Hatta', 'B' => 'Ir. Soekarno', 'C' => 'Soepomo', 'D' => 'Mohammad Yamin'], 'correct_answer' => 'B', 'explanation' => 'Ir. Soekarno mengusulkan nama "Pancasila" dalam sidang BPUPK pada tanggal 1 Juni 1945.'],
                    ['question' => 'Sila Pancasila yang mencerminkan prinsip demokrasi Indonesia adalah...', 'options' => ['A' => 'Sila ke-1', 'B' => 'Sila ke-2', 'C' => 'Sila ke-4', 'D' => 'Sila ke-5'], 'correct_answer' => 'C', 'explanation' => 'Sila ke-4 "Kerakyatan yang Dipimpin oleh Hikmat Kebijaksanaan dalam Permusyawaratan/Perwakilan" mencerminkan prinsip demokrasi.'],
                    ['question' => 'Piagam Jakarta disusun oleh...', 'options' => ['A' => 'Panitia Sembilan', 'B' => 'PPKI', 'C' => 'BPUPK', 'D' => 'Panitia Lima'], 'correct_answer' => 'A', 'explanation' => 'Piagam Jakarta disusun oleh Panitia Sembilan pada tanggal 22 Juni 1945.'],
                ],
                'uud-1945' => [
                    ['question' => 'UUD 1945 telah mengalami berapa kali amandemen?', 'options' => ['A' => '2 kali', 'B' => '3 kali', 'C' => '4 kali', 'D' => '5 kali'], 'correct_answer' => 'C', 'explanation' => 'UUD 1945 telah mengalami 4 kali amandemen: tahun 1999, 2000, 2001, dan 2002.'],
                    ['question' => 'Pembukaan UUD 1945 terdiri dari berapa alinea?', 'options' => ['A' => '3 alinea', 'B' => '4 alinea', 'C' => '5 alinea', 'D' => '6 alinea'], 'correct_answer' => 'B', 'explanation' => 'Pembukaan UUD 1945 terdiri dari 4 alinea yang memuat cita-cita dan tujuan bangsa Indonesia.'],
                ],
                'nkri-bhineka' => [
                    ['question' => 'Semboyan "Bhinneka Tunggal Ika" berasal dari kitab...', 'options' => ['A' => 'Negarakertagama', 'B' => 'Sutasoma', 'C' => 'Pararaton', 'D' => 'Arjunawiwaha'], 'correct_answer' => 'B', 'explanation' => 'Semboyan Bhinneka Tunggal Ika diambil dari Kitab Sutasoma karangan Mpu Tantular.'],
                ],
                'sejarah-nasional' => [
                    ['question' => 'Hari Kebangkitan Nasional diperingati setiap tanggal...', 'options' => ['A' => '2 Mei', 'B' => '20 Mei', 'C' => '28 Oktober', 'D' => '10 November'], 'correct_answer' => 'B', 'explanation' => 'Hari Kebangkitan Nasional diperingati setiap 20 Mei, bertepatan dengan berdirinya Budi Utomo tahun 1908.'],
                ],
                'bahasa-indonesia' => [
                    ['question' => 'Penulisan yang sesuai dengan EYD/PUEBI adalah...', 'options' => ['A' => 'apotik', 'B' => 'apotek', 'C' => 'apotiik', 'D' => 'appotik'], 'correct_answer' => 'B', 'explanation' => 'Penulisan yang benar sesuai PUEBI adalah "apotek" bukan "apotik".'],
                ],
            ],
            'tiu' => [
                'verbal-analogi' => [
                    ['question' => 'PANAS : DINGIN = GELAP : ...', 'options' => ['A' => 'Siang', 'B' => 'Malam', 'C' => 'Terang', 'D' => 'Cahaya'], 'correct_answer' => 'C', 'explanation' => 'Hubungan antonim (berlawanan). Panas >< Dingin, maka Gelap >< Terang.'],
                    ['question' => 'Sinonim dari kata "KONKLUSI" adalah...', 'options' => ['A' => 'Pendahuluan', 'B' => 'Kesimpulan', 'C' => 'Pernyataan', 'D' => 'Sanggahan'], 'correct_answer' => 'B', 'explanation' => 'Konklusi berarti kesimpulan atau hasil akhir dari suatu penalaran.'],
                ],
                'numerik-aritmatika' => [
                    ['question' => 'Lanjutkan deret berikut: 3, 6, 12, 24, ...', 'options' => ['A' => '30', 'B' => '36', 'C' => '48', 'D' => '72'], 'correct_answer' => 'C', 'explanation' => 'Ini adalah deret geometri dengan rasio 2. 24 × 2 = 48.'],
                    ['question' => 'Jika harga sebuah buku Rp 45.000 dan mendapat diskon 20%, berapa harga setelah diskon?', 'options' => ['A' => 'Rp 36.000', 'B' => 'Rp 38.000', 'C' => 'Rp 40.000', 'D' => 'Rp 35.000'], 'correct_answer' => 'A', 'explanation' => 'Diskon 20% = 20/100 × 45.000 = 9.000. Harga setelah diskon = 45.000 - 9.000 = Rp 36.000.'],
                ],
                'logika-penalaran' => [
                    ['question' => 'Semua dokter adalah lulusan kedokteran. Ani adalah dokter. Kesimpulan yang benar adalah...', 'options' => ['A' => 'Ani bukan lulusan kedokteran', 'B' => 'Ani adalah lulusan kedokteran', 'C' => 'Semua lulusan kedokteran adalah dokter', 'D' => 'Tidak dapat disimpulkan'], 'correct_answer' => 'B', 'explanation' => 'Berdasarkan silogisme: Semua dokter = lulusan kedokteran, Ani = dokter, maka Ani = lulusan kedokteran.'],
                ],
                'figural-pola' => [
                    ['question' => 'Jika sebuah persegi diputar 90° searah jarum jam, kemudian dicerminkan secara horizontal, posisi titik di pojok kanan atas akan berada di...', 'options' => ['A' => 'Pojok kiri atas', 'B' => 'Pojok kanan bawah', 'C' => 'Pojok kiri bawah', 'D' => 'Pojok kanan atas'], 'correct_answer' => 'C', 'explanation' => 'Rotasi 90° CW memindahkan pojok kanan atas ke pojok kanan bawah. Pencerminan horizontal kemudian memindahkan ke pojok kiri bawah.'],
                ],
            ],
            'tkp' => [
                'pelayanan-publik' => [
                    ['question' => 'Seorang warga datang ke kantor Anda meminta bantuan, tetapi bukan wewenang bagian Anda. Apa yang Anda lakukan?', 'options' => ['A' => 'Menyuruhnya pergi', 'B' => 'Mengarahkan ke bagian yang berwenang dan membantu prosesnya', 'C' => 'Membiarkan saja', 'D' => 'Menyuruh kembali besok'], 'correct_answer' => 'B', 'explanation' => 'Sikap pelayanan publik yang baik adalah mengarahkan dan membantu warga menemukan bagian yang tepat.'],
                ],
                'jejaring-kerja' => [
                    ['question' => 'Rekan kerja Anda mengalami kesulitan menyelesaikan tugasnya. Sikap Anda...', 'options' => ['A' => 'Membiarkan karena bukan tugas Anda', 'B' => 'Menawarkan bantuan dan berbagi pengetahuan', 'C' => 'Melaporkan ke atasan', 'D' => 'Menggantikan pekerjaannya'], 'correct_answer' => 'B', 'explanation' => 'Membangun jejaring kerja yang baik dimulai dari kesediaan saling membantu dan berbagi pengetahuan.'],
                ],
                'sosial-budaya' => [
                    ['question' => 'Anda ditempatkan di daerah dengan budaya yang sangat berbeda. Sikap terbaik adalah...', 'options' => ['A' => 'Memaksakan budaya sendiri', 'B' => 'Mempelajari dan beradaptasi dengan budaya setempat', 'C' => 'Mengabaikan budaya setempat', 'D' => 'Meminta pindah tugas'], 'correct_answer' => 'B', 'explanation' => 'Sikap terbaik ASN adalah mempelajari, menghargai, dan beradaptasi dengan budaya setempat.'],
                ],
                'profesionalisme' => [
                    ['question' => 'Atasan memberikan tugas mendadak di luar jam kerja. Apa yang Anda lakukan?', 'options' => ['A' => 'Menolak karena sudah di luar jam kerja', 'B' => 'Mengerjakannya dengan penuh tanggung jawab', 'C' => 'Menunda sampai besok', 'D' => 'Mendelegasikan ke rekan lain'], 'correct_answer' => 'B', 'explanation' => 'Profesionalisme ASN ditunjukkan melalui sikap tanggung jawab dan dedikasi terhadap tugas, termasuk dalam situasi mendesak.'],
                ],
                'anti-radikalisme' => [
                    ['question' => 'Anda mendapati rekan kerja menyebarkan paham yang bertentangan dengan Pancasila di lingkungan kerja. Apa yang Anda lakukan?', 'options' => ['A' => 'Membiarkan saja', 'B' => 'Ikut menyebarkan', 'C' => 'Menegur dan melaporkan ke pihak berwenang', 'D' => 'Membahas di grup media sosial'], 'correct_answer' => 'C', 'explanation' => 'ASN wajib menegakkan nilai-nilai Pancasila dan melaporkan aktivitas yang bertentangan kepada pihak berwenang.'],
                ],
            ],
        ];

        $slug = $subTopic->slug;
        $qData = $questionsMap[$section][$slug] ?? [];
        foreach ($qData as $q) {
            PracticeQuestion::create([
                'sub_topic_id' => $subTopic->id,
                'question' => $q['question'],
                'options' => $q['options'],
                'correct_answer' => $q['correct_answer'],
                'explanation' => $q['explanation'],
            ]);
        }
    }
}
