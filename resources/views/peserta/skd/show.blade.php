<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#135bec",
                        "background-light": "#f6f6f8",
                        "background-dark": "#101622",
                    },
                    fontFamily: {
                        "display": ["Public Sans", "sans-serif"]
                    },
                    borderRadius: {"DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px"},
                },
            },
        }
    </script>
    <title>{{ config('app.name', 'Psikotes') }} - Persiapan Simulasi SKD</title>
</head>
<body class="bg-background-light dark:bg-background-dark font-display text-slate-900 dark:text-slate-100 min-h-screen">
<div class="relative flex h-auto min-h-screen w-full flex-col group/design-root overflow-x-hidden">
    <div class="layout-container flex h-full grow flex-col">
        <header class="flex items-center justify-between whitespace-nowrap border-b border-solid border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 px-6 md:px-20 py-4 sticky top-0 z-50">
            <div class="flex items-center gap-4 text-primary">
                <div class="size-8">
                    <svg fill="none" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                        <path d="M13.8261 30.5736C16.7203 29.8826 20.2244 29.4783 24 29.4783C27.7756 29.4783 31.2797 29.8826 34.1739 30.5736C36.9144 31.2278 39.9967 32.7669 41.3563 33.8352L24.8486 7.36089C24.4571 6.73303 23.5429 6.73303 23.1514 7.36089L6.64374 33.8352C8.00331 32.7669 11.0856 31.2278 13.8261 30.5736Z" fill="currentColor"></path>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M39.998 35.764C39.9944 35.7463 39.9875 35.7155 39.9748 35.6706C39.9436 35.5601 39.8949 35.4259 39.8346 35.2825C39.8168 35.2403 39.7989 35.1993 39.7813 35.1602C38.5103 34.2887 35.9788 33.0607 33.7095 32.5189C30.9875 31.8691 27.6413 31.4783 24 31.4783C20.3587 31.4783 17.0125 31.8691 14.2905 32.5189C12.0012 33.0654 9.44505 34.3104 8.18538 35.1832C8.17384 35.2075 8.16216 35.233 8.15052 35.2592C8.09919 35.3751 8.05721 35.4886 8.02977 35.589C8.00356 35.6848 8.00039 35.7333 8.00004 35.7388C8.00004 35.739 8 35.7393 8.00004 35.7388C8.00004 35.7641 8.0104 36.0767 8.68485 36.6314C9.34546 37.1746 10.4222 37.7531 11.9291 38.2772C14.9242 39.319 19.1919 40 24 40C28.8081 40 33.0758 39.319 36.0709 38.2772C37.5778 37.7531 38.6545 37.1746 39.3151 36.6314C39.9006 36.1499 39.9857 35.8511 39.998 35.764ZM4.95178 32.7688L21.4543 6.30267C22.6288 4.4191 25.3712 4.41909 26.5457 6.30267L43.0534 32.777C43.0709 32.8052 43.0878 32.8338 43.104 32.8629L41.3563 33.8352C43.104 32.8629 43.1038 32.8626 43.104 32.8629L43.1051 32.865L43.1065 32.8675L43.1101 32.8739L43.1199 32.8918C43.1276 32.906 43.1377 32.9246 43.1497 32.9473C43.1738 32.9925 43.2062 33.0545 43.244 33.1299C43.319 33.2792 43.4196 33.489 43.5217 33.7317C43.6901 34.1321 44 34.9311 44 35.7391C44 37.4427 43.003 38.7775 41.8558 39.7209C40.6947 40.6757 39.1354 41.4464 37.385 42.0552C33.8654 43.2794 29.133 44 24 44C18.867 44 14.1346 43.2794 10.615 42.0552C8.86463 41.4464 7.30529 40.6757 6.14419 39.7209C4.99695 38.7775 3.99999 37.4427 3.99999 35.7391C3.99999 34.8725 4.29264 34.0922 4.49321 33.6393C4.60375 33.3898 4.71348 33.1804 4.79687 33.0311C4.83898 32.9556 4.87547 32.8935 4.9035 32.8471C4.91754 32.8238 4.92954 32.8043 4.93916 32.7889L4.94662 32.777L4.95178 32.7688ZM35.9868 29.004L24 9.77997L12.0131 29.004C12.4661 28.8609 12.9179 28.7342 13.3617 28.6282C16.4281 27.8961 20.0901 27.4783 24 27.4783C27.9099 27.4783 31.5719 27.8961 34.6383 28.6282C35.082 28.7342 35.5339 28.8609 35.9868 29.004Z" fill="currentColor"></path>
                    </svg>
                </div>
                <h2 class="text-slate-900 dark:text-white text-xl font-bold leading-tight tracking-tight">CAT BKN Online</h2>
            </div>
            <div class="flex flex-1 justify-end gap-8 items-center">
                <nav class="hidden md:flex items-center gap-8">
                    <a class="text-slate-600 dark:text-slate-300 text-sm font-medium hover:text-primary transition-colors" href="{{ route('peserta.dashboard') }}">Dashboard</a>
                    <a class="text-slate-600 dark:text-slate-300 text-sm font-medium hover:text-primary transition-colors" href="{{ route('peserta.skd.results') }}">Riwayat</a>
                    <a class="text-slate-600 dark:text-slate-300 text-sm font-medium hover:text-primary transition-colors" href="{{ route('peserta.skd.index') }}">Materi</a>
                    <a class="text-slate-600 dark:text-slate-300 text-sm font-medium hover:text-primary transition-colors" href="#">Bantuan</a>
                </nav>
                <div class="flex items-center gap-3">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-bold text-slate-900 dark:text-white">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-slate-500">Peserta Simulasi</p>
                    </div>
                    @php
                        $initials = collect(explode(' ', auth()->user()->name))->map(fn($segment) => strtoupper(substr($segment, 0, 1)))->take(2)->join('');
                    @endphp
                    <div class="bg-primary/10 border border-primary/20 aspect-square bg-cover rounded-full size-10 flex items-center justify-center text-primary font-bold">
                        {{ $initials }}
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 px-4 md:px-20 lg:px-40 py-8">
            <div class="max-w-5xl mx-auto flex flex-col gap-8">
                <div class="flex flex-col gap-2">
                    <div class="flex items-center gap-2 text-sm text-primary font-medium mb-2">
                        <a href="{{ route('peserta.dashboard') }}">Dashboard</a>
                        <span class="material-symbols-outlined text-sm">chevron_right</span>
                        <a href="{{ route('peserta.skd.index') }}" class="text-slate-400">Persiapan Ujian</a>
                    </div>
                    <h1 class="text-slate-900 dark:text-white text-3xl md:text-4xl font-black leading-tight tracking-tight">{{ $skdPackage->title }}</h1>
                    <p class="text-slate-500 dark:text-slate-400 text-lg">{{ $skdPackage->description ?: 'Sistem Computer Assisted Test (CAT) sesuai standar Badan Kepegawaian Negara.' }}</p>
                </div>
                
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <div class="lg:col-span-2 flex flex-col gap-6">
                        <section class="bg-white dark:bg-slate-900 p-6 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800">
                            <div class="flex items-center gap-3 mb-4">
                                <span class="material-symbols-outlined text-primary">gavel</span>
                                <h2 class="text-xl font-bold text-slate-900 dark:text-white">Tata Tertib &amp; Ketentuan</h2>
                            </div>
                            <div class="space-y-4 text-slate-600 dark:text-slate-400 leading-relaxed">
                                <div class="flex gap-4">
                                    <div class="size-6 rounded-full bg-primary/10 text-primary flex items-center justify-center flex-shrink-0 font-bold text-xs">1</div>
                                    <p>Durasi pengerjaan ujian adalah <span class="font-bold text-slate-900 dark:text-white">{{ $skdPackage->duration_minutes }} menit</span> untuk kategori umum.</p>
                                </div>
                                <div class="flex gap-4">
                                    <div class="size-6 rounded-full bg-primary/10 text-primary flex items-center justify-center flex-shrink-0 font-bold text-xs">2</div>
                                    <p>Total soal sebanyak <span class="font-bold text-slate-900 dark:text-white">{{ $skdPackage->total_questions }} butir</span> yang terdiri dari TWK, TIU, dan TKP.</p>
                                </div>
                                <div class="flex gap-4">
                                    <div class="size-6 rounded-full bg-primary/10 text-primary flex items-center justify-center flex-shrink-0 font-bold text-xs">3</div>
                                    <p>Dilarang membuka tab browser lain, aplikasi perekam layar, atau mencari jawaban di internet selama ujian.</p>
                                </div>
                                <div class="flex gap-4">
                                    <div class="size-6 rounded-full bg-primary/10 text-primary flex items-center justify-center flex-shrink-0 font-bold text-xs">4</div>
                                    <p>Ujian akan otomatis berhenti dan tersimpan jika waktu habis atau jika terdeteksi melakukan kecurangan.</p>
                                </div>
                            </div>
                        </section>

                        <section class="bg-white dark:bg-slate-900 p-6 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800">
                            <div class="flex items-center gap-3 mb-6">
                                <span class="material-symbols-outlined text-primary">analytics</span>
                                <h2 class="text-xl font-bold text-slate-900 dark:text-white">Ambang Batas (Passing Grade)</h2>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                @foreach($skdPackage->packageTests as $pt)
                                <div class="bg-background-light dark:bg-slate-800 p-4 rounded-lg border border-slate-100 dark:border-slate-700">
                                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-1">{{ strtoupper($pt->sub_test_type) }}</p>
                                    <p class="text-2xl font-black text-primary">{{ $pt->passing_grade }}</p>
                                    <p class="text-xs text-slate-400 mt-1">{{ $pt->sub_test_label }}</p>
                                </div>
                                @endforeach
                            </div>
                        </section>

                        <section class="bg-primary/5 border border-primary/20 p-6 rounded-xl flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="size-12 rounded-full bg-primary text-white flex items-center justify-center">
                                    <span class="material-symbols-outlined">verified_user</span>
                                </div>
                                <div>
                                    <h3 class="font-bold text-slate-900 dark:text-white">Konfirmasi Data Peserta</h3>
                                    <p class="text-sm text-slate-600 dark:text-slate-400">Pastikan identitas Anda sudah benar sebelum menekan tombol mulai.</p>
                                </div>
                            </div>
                            <form action="{{ route('peserta.skd.start', $skdPackage) }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-primary hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-bold transition-all shadow-lg shadow-primary/25">Mulai Ujian Sekarang</button>
                            </form>
                        </section>
                    </div>
                    
                    <div class="flex flex-col gap-6">
                        <div class="bg-white dark:bg-slate-900 p-6 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800">
                            <h3 class="font-bold text-slate-900 dark:text-white mb-4">Pemeriksaan Sistem</h3>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                                    <div class="flex items-center gap-2">
                                        <span class="material-symbols-outlined text-green-600 text-sm">wifi</span>
                                        <span class="text-sm font-medium text-green-800 dark:text-green-300">Koneksi Internet</span>
                                    </div>
                                    <span class="material-symbols-outlined text-green-600">check_circle</span>
                                </div>
                                <div class="flex items-center justify-between p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                                    <div class="flex items-center gap-2">
                                        <span class="material-symbols-outlined text-green-600 text-sm">browser_updated</span>
                                        <span class="text-sm font-medium text-green-800 dark:text-green-300">Browser Kompatibel</span>
                                    </div>
                                    <span class="material-symbols-outlined text-green-600">check_circle</span>
                                </div>
                                <div class="flex items-center justify-between p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                                    <div class="flex items-center gap-2">
                                        <span class="material-symbols-outlined text-yellow-600 text-sm">battery_charging_80</span>
                                        <span class="text-sm font-medium text-yellow-800 dark:text-yellow-300">Daya Baterai</span>
                                    </div>
                                    <span class="text-xs font-bold text-yellow-700 dark:text-yellow-400">82%</span>
                                </div>
                            </div>
                            <div class="mt-6 p-4 border-t border-slate-100 dark:border-slate-800">
                                <p class="text-xs text-slate-400 leading-relaxed italic text-center">
                                    Sistem kami mendeteksi Anda menggunakan browser kompatibel. Gunakan mode layar penuh untuk pengalaman terbaik.
                                </p>
                            </div>
                        </div>
                        
                        <div class="relative overflow-hidden rounded-xl bg-slate-900 aspect-video group">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent z-10 flex flex-col justify-end p-4">
                                <p class="text-white font-bold text-sm">Video Panduan CAT BKN</p>
                                <p class="text-slate-300 text-xs">Durasi 2:45 menit</p>
                            </div>
                            <img class="w-full h-full object-cover opacity-60 group-hover:scale-105 transition-transform duration-500" data-alt="Tangan seseorang sedang mengerjakan ujian di laptop" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCrI7QN9GTKTgbSok4YzcByQ465u57Kt6-qUBn_KaAnHIVESb8bMekmTtvTsFa_5-8HtbFNSz7oSG78b1sDoqc9FlLn6bWJ2pDraVq5EHi5GFaamiTZyf_xIG-XlZvd3wvqiUtlLHRt8-Lw87EEqfwVtE3z3g57mr7ZBDv5n26Egd26Jq-4Ws9809LL5yi8scmkKfXs3uduEje22syXn27kX7KCsAXM5GmDqZxYiGGs0TROT_ZrcEp8vbsGL5IuIKPi074xarcoHfYe"/>
                            <div class="absolute inset-0 flex items-center justify-center z-20">
                                <div class="size-12 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center text-white border border-white/30 cursor-pointer hover:bg-white/40 transition-colors">
                                    <span class="material-symbols-outlined text-3xl">play_arrow</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white dark:bg-slate-900 p-6 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800">
                            <h3 class="font-bold text-slate-900 dark:text-white mb-2">Butuh Bantuan?</h3>
                            <p class="text-sm text-slate-500 dark:text-slate-400 mb-4">Jika mengalami kendala teknis, silakan hubungi pengawas atau admin melalui live chat.</p>
                            <button class="w-full py-2 border border-primary text-primary font-bold rounded hover:bg-primary/5 transition-colors flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-sm">support_agent</span>
                                Hubungi Admin
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        
        <footer class="mt-12 border-t border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 px-6 md:px-20 py-8">
            <div class="max-w-5xl mx-auto flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex items-center gap-2 opacity-50">
                    <span class="material-symbols-outlined text-lg">verified</span>
                    <p class="text-xs font-medium">Badan Kepegawaian Negara - Simulasi Mandiri 2024</p>
                </div>
                <div class="flex gap-6 text-xs text-slate-500">
                    <a class="hover:text-primary" href="#">Syarat &amp; Ketentuan</a>
                    <a class="hover:text-primary" href="#">Kebijakan Privasi</a>
                    <a class="hover:text-primary" href="#">Panduan CAT</a>
                </div>
            </div>
        </footer>
    </div>
</div>
</body>
</html>
