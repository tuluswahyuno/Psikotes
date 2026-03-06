<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>CAT BKN - Simulasi SKD CPNS 2024</title>
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#135bec",
                        "background-light": "#f6f6f8",
                        "background-dark": "#101622",
                        "success": "#22c55e",
                        "warning": "#eab308",
                        "error": "#ef4444",
                    },
                    fontFamily: {
                        "display": ["Public Sans"]
                    },
                    borderRadius: {"DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px"},
                },
            },
        }
    </script>
    <style>
        body { font-family: 'Public Sans', sans-serif; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        /* Prevent selection for exam security */
        .prevent-select {
            -webkit-user-select: none; /* Safari */
            -ms-user-select: none; /* IE 10 and IE 11 */
            user-select: none; /* Standard syntax */
        }
    </style>
</head>
<body class="bg-background-light text-slate-900 font-display min-h-screen prevent-select">
    <div class="flex flex-col h-screen overflow-hidden">
        
        <!-- Header Section -->
        <header class="flex items-center justify-between border-b border-slate-200 bg-white px-4 md:px-8 py-4 z-10 shadow-sm shrink-0">
            <div class="flex items-center gap-4">
                <div class="p-2 bg-primary rounded-lg text-white">
                    <span class="material-symbols-outlined block">description</span>
                </div>
                <div>
                    <h1 class="text-[15px] md:text-lg font-bold leading-tight">CAT BKN - {{ $skdPackage->title }}</h1>
                    <p class="text-[10px] md:text-xs text-slate-500">Seleksi Kompetensi Dasar (SKD)</p>
                </div>
            </div>
            
            <div class="flex items-center gap-3 md:gap-6">
                <!-- Countdown Timer -->
                <div class="flex gap-1 md:gap-2">
                    <div class="flex flex-col items-center">
                        <div id="h-val" class="bg-slate-100 px-2 md:px-3 py-1 rounded font-bold text-primary min-w-[36px] md:min-w-[48px] text-center text-lg md:text-xl">00</div>
                        <span class="text-[8px] md:text-[10px] uppercase font-bold text-slate-400">Jam</span>
                    </div>
                    <div class="text-lg md:text-xl font-bold self-start mt-1">:</div>
                    <div class="flex flex-col items-center">
                        <div id="m-val" class="bg-slate-100 px-2 md:px-3 py-1 rounded font-bold text-primary min-w-[36px] md:min-w-[48px] text-center text-lg md:text-xl">00</div>
                        <span class="text-[8px] md:text-[10px] uppercase font-bold text-slate-400">Menit</span>
                    </div>
                    <div class="text-lg md:text-xl font-bold self-start mt-1">:</div>
                    <div class="flex flex-col items-center">
                        <div id="s-val" class="bg-slate-100 px-2 md:px-3 py-1 rounded font-bold text-primary min-w-[36px] md:min-w-[48px] text-center text-lg md:text-xl">00</div>
                        <span class="text-[8px] md:text-[10px] uppercase font-bold text-slate-400">Detik</span>
                    </div>
                </div>
                
                <div class="h-10 w-[1px] bg-slate-200 hidden md:block mx-1"></div>
                
                <!-- Profile Indicator -->
                <div class="hidden md:flex items-center gap-3">
                    <div class="text-right">
                        <p class="text-sm font-bold">{{ auth()->user()->name }}</p>
                        <p class="text-[10px] text-slate-500">{{ auth()->user()->id }}{{ date('Ymd') }}001</p>
                    </div>
                    <div class="h-10 w-10 rounded-full bg-slate-200 bg-cover bg-center border-2 border-primary/20" 
                        style="background-image: url('https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=e2e8f0&color=0f172a&bold=true')">
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content Area -->
        <main class="flex flex-1 overflow-hidden">
            
            <!-- Left: Question Area -->
            <div class="flex-1 flex flex-col overflow-y-auto bg-white p-4 md:p-8">
                
                <!-- Question Header & Controls -->
                <div class="flex justify-between items-center mb-6 md:mb-8 sticky top-0 bg-white/95 backdrop-blur-sm z-10 p-2 -mx-2 rounded-xl">
                    <div class="px-4 py-2 bg-primary/10 text-primary rounded-lg font-bold border border-primary/20 text-sm">
                        Soal Nomor <span id="current-q-num">1</span>
                    </div>
                    <div class="flex gap-1 md:gap-2 bg-slate-100 p-1 rounded-lg">
                        <button onclick="adjustFont('up')" class="flex items-center p-1.5 md:p-2 hover:bg-white rounded-md transition-colors gap-1" title="Perbesar Huruf">
                            <span class="material-symbols-outlined text-sm md:text-base">zoom_in</span>
                            <span class="text-[10px] md:text-xs font-bold">A+</span>
                        </button>
                        <button onclick="adjustFont('reset')" class="flex items-center p-1.5 md:p-2 bg-white shadow-sm rounded-md transition-colors gap-1" title="Ukuran Normal">
                            <span class="material-symbols-outlined text-sm md:text-base">text_fields</span>
                            <span class="text-[10px] md:text-xs font-bold">A</span>
                        </button>
                        <button onclick="adjustFont('down')" class="flex items-center p-1.5 md:p-2 hover:bg-white rounded-md transition-colors gap-1" title="Perkecil Huruf">
                            <span class="material-symbols-outlined text-sm md:text-base">zoom_out</span>
                            <span class="text-[10px] md:text-xs font-bold">A-</span>
                        </button>
                    </div>
                </div>

                <!-- Shared Questions Container -->
                <div class="flex-1 relative">
                    @php $globalIdx = 1; @endphp
                    @foreach($questionsByType as $type => $data)
                        <div id="subtest-{{ $type }}" class="subtest-container {{ $loop->first ? '' : 'hidden' }}">
                            @foreach($data['questions'] as $qIdx => $question)
                                <div class="question-card {{ $globalIdx == 1 ? '' : 'hidden' }}" 
                                    data-question-id="{{ $question->id }}"
                                    id="q-card-{{ $question->id }}"
                                    data-global-idx="{{ $globalIdx }}"
                                    data-type="{{ $type }}">
                                    
                                    <!-- Question Text -->
                                    <div class="question-text mb-8 md:mb-10 text-base md:text-lg leading-relaxed text-slate-800" style="font-size: 1.125rem;">
                                        {!! nl2br(e($question->question_text)) !!}
                                    </div>

                                    <!-- Answer Options -->
                                    <div class="space-y-3 md:space-y-4 max-w-4xl">
                                        @php $letters = array('A', 'B', 'C', 'D', 'E'); @endphp
                                        @foreach($question->options as $oIdx => $option)
                                        <label class="option-row flex items-start gap-3 md:gap-4 p-3 md:p-4 rounded-xl cursor-pointer transition-all group border
                                            {{ (isset($existingAnswers[$question->id]) && $existingAnswers[$question->id]['option_id'] == $option->id) 
                                                ? 'border-primary bg-primary/5' 
                                                : 'border-slate-200 hover:border-primary/50' }}"
                                            onclick="saveAnswer({{ $question->id }}, {{ $option->id }}, this)">
                                            
                                            <input type="radio" name="q-{{ $question->id }}" value="{{ $option->id }}" 
                                                class="mt-1 md:mt-1.5 w-4 h-4 md:w-5 md:h-5 text-primary focus:ring-primary border-slate-300"
                                                {{ (isset($existingAnswers[$question->id]) && $existingAnswers[$question->id]['option_id'] == $option->id) ? 'checked' : '' }}
                                                onchange="saveAnswer({{ $question->id }}, {{ $option->id }}, this.closest('.option-row'))">
                                            
                                            <div class="flex gap-2 md:gap-4 flex-1">
                                                <span class="font-bold text-sm md:text-base {{ (isset($existingAnswers[$question->id]) && $existingAnswers[$question->id]['option_id'] == $option->id) ? 'text-primary' : 'text-slate-400 group-hover:text-primary' }}">
                                                    {{ $letters[$oIdx] }}.
                                                </span>
                                                <span class="text-sm md:text-base leading-snug {{ (isset($existingAnswers[$question->id]) && $existingAnswers[$question->id]['option_id'] == $option->id) ? 'text-slate-900 font-medium' : 'text-slate-700' }}">
                                                    {{ $option->option_text }}
                                                </span>
                                            </div>
                                        </label>
                                        @endforeach
                                    </div>
                                </div>
                                @php $globalIdx++; @endphp
                            @endforeach
                        </div>
                    @endforeach
                </div>

                <!-- Footer Navigate Controls -->
                <div class="mt-8 md:mt-12 pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <button type="button" onclick="prevQuestion()" class="w-full sm:w-auto flex items-center justify-center gap-2 px-6 py-2.5 bg-slate-100 text-slate-700 border border-slate-200 rounded-lg font-bold hover:bg-slate-200 transition-colors">
                        <span class="material-symbols-outlined text-lg">chevron_left</span>
                        Sebelumnya
                    </button>
                    
                    <label class="w-full sm:w-auto flex items-center justify-center gap-3 cursor-pointer select-none px-6 py-2.5 bg-warning/10 border border-warning/30 text-warning-700 rounded-lg hover:bg-warning/20 transition-colors">
                        <input type="checkbox" id="doubtful-input" class="w-4 h-4 md:w-5 md:h-5 rounded border-warning text-warning focus:ring-warning" onchange="toggleDoubtful()">
                        <span class="font-bold text-warning-800 text-sm md:text-base">Tandai Ragu-ragu</span>
                    </label>

                    <button type="button" onclick="nextQuestion()" class="w-full sm:w-auto flex items-center justify-center gap-2 px-8 py-2.5 bg-primary text-white rounded-lg font-bold hover:bg-primary/90 transition-colors">
                        Selanjutnya
                        <span class="material-symbols-outlined text-lg">chevron_right</span>
                    </button>
                </div>

            </div>

            <!-- Right: Navigation Panel -->
            <aside class="w-full md:w-80 lg:w-96 flex flex-col bg-slate-50 border-t md:border-t-0 md:border-l border-slate-200 shrink-0 h-[40vh] md:h-auto">
                <div class="p-4 md:p-6 border-b border-slate-200 bg-white">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-slate-500">Navigasi Soal</h3>
                    <p class="text-[10px] md:text-xs text-slate-400 mt-1">Total {{ $skdPackage->total_questions }} Butir Pertanyaan</p>
                </div>
                
                <!-- Number Grid scroll wrapper -->
                <div class="flex-1 overflow-y-auto p-4 bg-slate-50 no-scrollbar">
                    <div class="grid grid-cols-5 md:grid-cols-4 lg:grid-cols-5 gap-2">
                        @php $navIdx = 1; @endphp
                        @foreach($questionsByType as $type => $data)
                            @foreach($data['questions'] as $qIdx => $q)
                            <button type="button" 
                                onclick="goToGlobalQuestion({{ $navIdx }})"
                                id="nav-btn-{{ $q->id }}"
                                class="nav-btn aspect-square flex items-center justify-center text-[10px] md:text-xs font-bold rounded-lg transition-all shadow-sm focus:outline-none
                                @php
                                    $status = 'unvisited';
                                    if(isset($existingAnswers[$q->id]) && ($existingAnswers[$q->id]['option_id'] || $existingAnswers[$q->id]['is_doubtful'])) {
                                        $status = $existingAnswers[$q->id]['is_doubtful'] ? 'doubtful' : 'answered';
                                    }
                                @endphp
                                {{ $status == 'answered' ? 'bg-success text-white border-b-2 border-success-700' : '' }}
                                {{ $status == 'doubtful' ? 'bg-warning text-white border-b-2 border-warning-700' : '' }}
                                {{ $status == 'unvisited' ? 'bg-white border border-slate-200 text-slate-400' : '' }}">
                                {{ str_pad($navIdx, 2, '0', STR_PAD_LEFT) }}
                            </button>
                            @php $navIdx++; @endphp
                            @endforeach
                        @endforeach
                    </div>
                </div>

                <!-- Footer Summary & Action -->
                <div class="p-4 md:p-6 space-y-4 bg-white border-t border-slate-200">
                    <div class="grid grid-cols-3 gap-2 text-[9px] md:text-[10px] font-bold uppercase">
                        <div class="flex flex-col items-center gap-1">
                            <div class="w-full h-1 bg-success rounded-full opacity-80"></div>
                            <span class="text-success text-center">Sudah: <span id="stat-answered">0</span></span>
                        </div>
                        <div class="flex flex-col items-center gap-1">
                            <div class="w-full h-1 bg-warning rounded-full opacity-80"></div>
                            <span class="text-warning text-center">Ragu: <span id="stat-doubtful">0</span></span>
                        </div>
                        <div class="flex flex-col items-center gap-1">
                            <div class="w-full h-1 bg-error rounded-full opacity-80"></div>
                            <span class="text-error text-center">Belum: <span id="stat-unanswered">0</span></span>
                        </div>
                    </div>
                    
                    <form action="{{ route('peserta.skd.submit', $skdSession) }}" method="POST" id="submitForm">
                        @csrf
                        <button type="button" onclick="confirmSubmit()" class="w-full py-3 md:py-4 bg-primary hover:bg-primary/90 text-white font-bold rounded-xl shadow-lg shadow-primary/20 transition-all flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-lg">check_circle</span>
                            SELESAI UJIAN
                        </button>
                    </form>
                    <p class="text-center text-[9px] md:text-[10px] text-slate-400 italic">Pastikan semua jawaban telah diperiksa kembali.</p>
                </div>
            </aside>
            
        </main>
    </div>

<script>
    // State Initialization
    const sessionRemainingSeconds = {{ $skdSession->remaining_time }};
    const sessionId = {{ $skdSession->id }};
    const totalQuestions = {{ $skdPackage->total_questions }};
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    let remaining = sessionRemainingSeconds;
    let answers = @json($existingAnswers);
    let fontSizeLevel = 1.125; // in rem (18px)
    let currentGlobalIdx = 1;
    let visitedNodes = [1];

    // CSRF Utility
    function getFetchConfig(bodyData) {
        return {
            method: 'POST',
            headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken},
            body: JSON.stringify(bodyData)
        };
    }

    // Timer logic
    function updateTimer() {
        if (remaining <= 0) {
            document.getElementById('submitForm').submit();
            return;
        }
        const h = Math.floor(remaining / 3600);
        const m = Math.floor((remaining % 3600) / 60);
        const s = remaining % 60;
        
        document.getElementById('h-val').textContent = String(h).padStart(2,'0');
        document.getElementById('m-val').textContent = String(m).padStart(2,'0');
        document.getElementById('s-val').textContent = String(s).padStart(2,'0');
        remaining--;
    }
    updateTimer();
    setInterval(updateTimer, 1000);

    // Font Adjustment
    function adjustFont(action) {
        if (action === 'up') fontSizeLevel += 0.1;
        else if (action === 'down') fontSizeLevel -= 0.1;
        else fontSizeLevel = 1.125;
        document.querySelectorAll('.question-text').forEach(el => el.style.fontSize = `${fontSizeLevel}rem`);
    }

    function goToGlobalQuestion(idx) {
        if(!visitedNodes.includes(idx)) {
            visitedNodes.push(idx);
        }

        const allCards = document.querySelectorAll('.question-card');
        let targetCard = null;
        allCards.forEach(c => {
            if (parseInt(c.getAttribute('data-global-idx')) === idx) {
                targetCard = c;
            }
            c.classList.add('hidden');
        });

        if (targetCard) {
            targetCard.classList.remove('hidden');
            const type = targetCard.getAttribute('data-type');
            
            document.querySelectorAll('.subtest-container').forEach(sc => sc.classList.add('hidden'));
            document.getElementById(`subtest-${type}`).classList.remove('hidden');
            
            // Clean up old active style before changing index
            if(currentGlobalIdx !== idx) {
                const oldCard = document.querySelector(`.question-card[data-global-idx="${currentGlobalIdx}"]`);
                if(oldCard) updateNavVisual(oldCard.getAttribute('data-question-id'), currentGlobalIdx, false);
            }

            currentGlobalIdx = idx;
            document.getElementById('current-q-num').textContent = idx;
            
            const qId = targetCard.getAttribute('data-question-id');
            updateNavVisual(qId, idx, true); // Set new active

            // Sync doubtful state
            const doubtfulInput = document.getElementById('doubtful-input');
            if (answers[qId] && answers[qId].is_doubtful) {
                doubtfulInput.checked = true;
            } else {
                doubtfulInput.checked = false;
            }
        }
    }

    function nextQuestion() {
        if (currentGlobalIdx < totalQuestions) goToGlobalQuestion(currentGlobalIdx + 1);
    }
    
    function prevQuestion() {
        if (currentGlobalIdx > 1) goToGlobalQuestion(currentGlobalIdx - 1);
    }

    function toggleDoubtful() {
        const card = document.querySelector(`.question-card:not(.hidden)`);
        const qId = card.getAttribute('data-question-id');
        const isChecked = document.getElementById('doubtful-input').checked;

        fetch(`/peserta/skd-sessions/${sessionId}/answer`, getFetchConfig({ question_id: qId, is_doubtful: isChecked }))
        .then(() => {
            if (!answers[qId]) answers[qId] = { option_id: null, is_doubtful: false };
            answers[qId].is_doubtful = isChecked;
            updateNavVisual(qId, currentGlobalIdx, true);
            updateStats();
        });
    }

    let isSaving = false;
    
    function saveAnswer(questionId, optionId, elementWrapper) {
        if(isSaving) return;
        isSaving = true;

        fetch(`/peserta/skd-sessions/${sessionId}/answer`, getFetchConfig({ question_id: questionId, option_id: optionId }))
        .then(() => {
            const card = document.getElementById(`q-card-${questionId}`);
            
            // Atur ulang semua baris (radio) di soal
            card.querySelectorAll('.option-row').forEach(row => {
                row.classList.remove('border-primary', 'bg-primary/5');
                row.classList.add('border-slate-200');
                
                // Pastikan yang diuncheck hanya yang bukan opsi yang sedang diklik (ini penting untuk mencegah behavior aneh saat label diklik radio native reset)
                const radio = row.querySelector('input[type="radio"]');
                if(radio.value != optionId) radio.checked = false;
                
                // Kembalikan abjad opsi ke warna abu-abu
                const spans = row.querySelectorAll('span');
                if(spans.length >= 2) {
                    spans[0].classList.remove('text-primary'); 
                    spans[0].classList.add('text-slate-400'); // Answer Letter
                    
                    spans[1].classList.remove('text-slate-900', 'font-medium'); 
                    spans[1].classList.add('text-slate-700'); // Answer Text
                }
            });
            
            // Gunakan elementWrapper yang di-passing dari event
            if(elementWrapper) {
                elementWrapper.classList.replace('border-slate-200', 'border-primary');
                elementWrapper.classList.add('bg-primary/5');
                elementWrapper.querySelector('input[type="radio"]').checked = true;
                
                // Update teks ke biru dan bold
                const spans = elementWrapper.querySelectorAll('span');
                if(spans.length >= 2) {
                    spans[0].classList.replace('text-slate-400', 'text-primary'); 
                    spans[1].classList.replace('text-slate-700', 'text-slate-900'); 
                    spans[1].classList.add('font-medium');
                }
            }

            if (!answers[questionId]) answers[questionId] = { option_id: null, is_doubtful: false };
            answers[questionId].option_id = optionId;
            updateNavVisual(questionId, currentGlobalIdx, true);
            updateStats();
        })
        .finally(() => {
            // Tunggu sebentar untuk mencegah event bubbling dari click label ke click radio
            setTimeout(() => { isSaving = false; }, 100);
        });
    }

    function updateNavVisual(qId, specificIdx, isActive) {
        const btn = document.getElementById(`nav-btn-${qId}`);
        if(!btn) return;
        
        let hasAnswer = answers[qId] && answers[qId].option_id;
        let isDoubt = answers[qId] && answers[qId].is_doubtful;
        let hasBeenVisited = visitedNodes.includes(specificIdx) || hasAnswer || isDoubt;

        // Reset all classes mapped to visual state
        btn.className = "nav-btn aspect-square flex items-center justify-center text-[10px] md:text-xs font-bold rounded-lg transition-all shadow-sm focus:outline-none";

        if (isActive) {
            btn.classList.add('bg-primary', 'text-white', 'ring-2', 'ring-offset-2', 'ring-primary', 'border-none');
            // Theme toggle support for rings if they implement dark mode in tailwind
            // Here using generic offset usually slate-50 background handling
        } else if (isDoubt) {
            btn.classList.add('bg-warning', 'text-white', 'border-b-2', 'border-warning-700');
        } else if (hasAnswer) {
            btn.classList.add('bg-success', 'text-white', 'border-b-2', 'border-success-700');
        } else if (hasBeenVisited) {
            // Unanswered but visited = Red
            btn.classList.add('bg-error', 'text-white', 'border-b-2', 'border-error-700');
        } else {
            // Unvisited = White border slate
            btn.classList.add('bg-white', 'border', 'border-slate-200', 'text-slate-400');
        }
    }

    function updateStats() {
        let sc = 0, rg = 0;
        Object.values(answers).forEach(a => { if (a.is_doubtful) rg++; else if (a.option_id) sc++; });
        document.getElementById('stat-answered').textContent = sc;
        document.getElementById('stat-doubtful').textContent = rg;
        document.getElementById('stat-unanswered').textContent = totalQuestions - sc - rg;
    }

    function confirmSubmit() {
        Swal.fire({
            title: 'Selesai Ujian?',
            text: 'Pastikan telah mengkaji ulang karena jawaban setelah dikirim tidak dapat diubah.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#135bec', // primary
            confirmButtonText: 'Ya, Akhiri Ujian',
            cancelButtonText: 'Kembali',
            customClass: { popup: 'rounded-xl', confirmButton: 'rounded-lg font-bold', cancelButton: 'rounded-lg font-bold' }
        }).then(res => { if (res.isConfirmed) document.getElementById('submitForm').submit(); });
    }

    // Init phase
    const allCardsInit = document.querySelectorAll('.question-card');
    allCardsInit.forEach(c => {
        const qId = c.getAttribute('data-question-id');
        const globalIdx = parseInt(c.getAttribute('data-global-idx'));
        if ((answers[qId] && (answers[qId].option_id || answers[qId].is_doubtful)) && !visitedNodes.includes(globalIdx)) {
            visitedNodes.push(globalIdx); 
        }
        if(globalIdx !== 1) updateNavVisual(qId, globalIdx, false); 
    });

    goToGlobalQuestion(1);
    updateStats();
</script>
</body>
</html>
