<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\SubTopic;
use App\Models\PracticeQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PracticeQuestionController extends Controller
{
    public function index(Request $request)
    {
        $sections = Section::with('subTopics')->get();
        $query = PracticeQuestion::with('subTopic.section');

        if ($request->filled('sub_topic_id')) {
            $query->where('sub_topic_id', $request->sub_topic_id);
        }
        if ($request->filled('section_id')) {
            $subTopicIds = SubTopic::where('section_id', $request->section_id)->pluck('id');
            $query->whereIn('sub_topic_id', $subTopicIds);
        }
        if ($request->filled('difficulty')) {
            $query->where('difficulty', $request->difficulty);
        }
        if ($request->filled('search')) {
            $query->where('question', 'like', '%' . $request->search . '%');
        }

        $questions = $query->latest()->paginate(15)->withQueryString();
        $totalCount = PracticeQuestion::count();

        return view('admin.practice-questions.index', compact('questions', 'sections', 'totalCount'));
    }

    public function create()
    {
        $sections = Section::with('subTopics')->get();
        return view('admin.practice-questions.form', compact('sections'));
    }

    public function store(Request $request)
    {
        $data = $this->validateQuestion($request);
        $data['options'] = [
            'A' => $request->option_a,
            'B' => $request->option_b,
            'C' => $request->option_c,
            'D' => $request->option_d,
            'E' => $request->option_e,
        ];
        $data['tags'] = $request->filled('tags')
            ? array_map('trim', explode(',', $request->tags))
            : null;

        PracticeQuestion::create($data);

        return redirect()->route('admin.practice-questions.index')
            ->with('success', 'Soal latihan berhasil ditambahkan!');
    }

    public function edit(PracticeQuestion $practiceQuestion)
    {
        $sections = Section::with('subTopics')->get();
        return view('admin.practice-questions.form', compact('sections', 'practiceQuestion'));
    }

    public function update(Request $request, PracticeQuestion $practiceQuestion)
    {
        $data = $this->validateQuestion($request);
        $data['options'] = [
            'A' => $request->option_a,
            'B' => $request->option_b,
            'C' => $request->option_c,
            'D' => $request->option_d,
            'E' => $request->option_e,
        ];
        $data['tags'] = $request->filled('tags')
            ? array_map('trim', explode(',', $request->tags))
            : null;

        $practiceQuestion->update($data);

        return redirect()->route('admin.practice-questions.index')
            ->with('success', 'Soal latihan berhasil diperbarui!');
    }

    public function destroy(PracticeQuestion $practiceQuestion)
    {
        $practiceQuestion->delete();
        return redirect()->route('admin.practice-questions.index')
            ->with('success', 'Soal latihan berhasil dihapus!');
    }

    // ─── Import Excel ─────────────────────────────────────────────────────────
    public function importForm()
    {
        $sections = Section::with('subTopics')->orderBy('name')->get();
        return view('admin.practice-questions.import', compact('sections'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:5120',
        ]);

        $file = $request->file('file');
        $extension = strtolower($file->getClientOriginalExtension());
        
        if ($extension !== 'csv') {
            return back()->withInput()->withErrors(['file' => 'Hanya support format file .csv (Bukan excel .xlsx)']);
        }

        $rows = $this->parseCsv($file->getRealPath());

        $errors = [];
        $imported = 0;
        $validKeys = ['A', 'B', 'C', 'D', 'E'];

        foreach ($rows as $i => $row) {
            $rowNum = $i + 2;
            
            $sectionInput = trim($row['section'] ?? '');
            $subTopicInput = trim($row['sub_topic'] ?? '');

            if (empty($sectionInput)) {
                $errors[] = "Baris {$rowNum}: Kolom 'section' tidak boleh kosong.";
                continue;
            }

            // Cari section berdasarkan exact slug, title, atau like title
            $section = Section::where('slug', \Illuminate\Support\Str::slug($sectionInput))
                ->orWhere('name', 'LIKE', "%{$sectionInput}%")
                ->orWhere('slug', $sectionInput)
                ->first();

            if (!$section) {
                $errors[] = "Baris {$rowNum}: Section '{$sectionInput}' tidak dikenali.";
                continue;
            }

            // Cari sub_topic berdasarkan exact slug, title, atau like title
            $sub_topic = SubTopic::where('section_id', $section->id)
                ->where(function($q) use ($subTopicInput) {
                    $q->where('slug', \Illuminate\Support\Str::slug($subTopicInput))
                      ->orWhere('title', 'LIKE', "%{$subTopicInput}%")
                      ->orWhere('slug', $subTopicInput);
                })
                ->first();

            if (!$sub_topic) {
                $errors[] = "Baris {$rowNum}: Sub-topik '{$subTopicInput}' pada section '{$section->name}' tidak dikenali.";
                continue;
            }
            if (!in_array(strtoupper(trim($row['correct_answer'] ?? '')), $validKeys)) {
                $errors[] = "Baris {$rowNum}: Kunci jawaban harus A/B/C/D/E.";
                continue;
            }

            PracticeQuestion::create([
                'sub_topic_id'   => $sub_topic->id,
                'question'       => $row['question'],
                'options'        => [
                    'A' => $row['option_a'],
                    'B' => $row['option_b'],
                    'C' => $row['option_c'],
                    'D' => $row['option_d'],
                    'E' => $row['option_e'],
                ],
                'correct_answer' => strtoupper(trim($row['correct_answer'])),
                'explanation'    => $row['explanation'] ?? null,
                'difficulty'     => in_array($row['difficulty'] ?? '', ['easy','medium','hard'])
                                        ? $row['difficulty'] : 'medium',
                'tags'           => !empty($row['tags'])
                                        ? array_map('trim', explode(',', $row['tags']))
                                        : null,
            ]);
            $imported++;
        }

        $msg = "{$imported} soal berhasil diimpor.";
        if (count($errors)) {
            $msg .= ' ' . count($errors) . ' soal gagal.';
            session()->flash('import_errors', $errors);
        }

        return redirect()->route('admin.practice-questions.index')->with('success', $msg);
    }

    public function downloadTemplate()
    {
        $headers = ['section','sub_topic','question','option_a','option_b','option_c','option_d','option_e','correct_answer','explanation','difficulty','tags'];
        $sample  = ['tiu','analogi','Contoh soal pertanyaan...','Pilihan A','Pilihan B','Pilihan C','Pilihan D','Pilihan E','A','Penjelasan jawaban...','medium','tag1, tag2'];

        $handle = fopen('php://output', 'w');
        ob_start();
        // UTF-8 BOM untuk kompatibilitas Excel
        echo "\xEF\xBB\xBF";
        // Menggunakan pemisah semicolon (;) agar tidak tergabung jadi 1 field di Excel region Indonesia
        fputcsv($handle, $headers, ';');
        fputcsv($handle, $sample, ';');
        fclose($handle);
        $content = ob_get_clean();

        return response($content, 200, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="template_soal_latihan.csv"',
        ]);
    }

    private function parseCsv(string $path): array
    {
        $rows = [];
        if (($handle = fopen($path, 'r')) !== false) {
            // Cek BOM dan lewati jika ada
            $bom = fread($handle, 3);
            if ($bom !== "\xEF\xBB\xBF") {
                rewind($handle);
            }

            // Baca baris pertama untuk deteksi separator (; atau ,)
            $firstLine = fgets($handle);
            rewind($handle);
            if ($bom === "\xEF\xBB\xBF") fread($handle, 3);

            $separator = (strpos($firstLine, ';') !== false) ? ';' : ',';

            $headers = fgetcsv($handle, 0, $separator);
            if (!$headers) {
                fclose($handle);
                return [];
            }
            // Bersihkan nama kolom dari whitespace tersembunyi
            $headers = array_map(function($h) { return trim(strtolower($h)); }, $headers);

            while (($data = fgetcsv($handle, 0, $separator)) !== false) {
                if (count($data) === count($headers)) {
                    $rows[] = array_combine($headers, $data);
                }
            }
            fclose($handle);
        }
        return $rows;
    }

    private function validateQuestion(Request $request): array
    {
        return $request->validate([
            'sub_topic_id'   => 'required|exists:sub_topics,id',
            'question'       => 'required|string',
            'option_a'       => 'required|string',
            'option_b'       => 'required|string',
            'option_c'       => 'required|string',
            'option_d'       => 'required|string',
            'option_e'       => 'required|string',
            'correct_answer' => 'required|in:A,B,C,D,E',
            'explanation'    => 'nullable|string',
            'difficulty'     => 'required|in:easy,medium,hard',
            'tags'           => 'nullable|string',
        ]);
    }
}
