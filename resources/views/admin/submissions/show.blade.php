@extends('admin.layouts.app')

@section('title', 'Detail Hasil - ' . $submission->candidate->name)
@section('page_title', 'Laporan Hasil Profil DISC Kandidat')

@section('content')
<div class="space-y-6">

    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 pb-2">
        <a href="{{ route('admin.submissions.index') }}" class="inline-flex items-center space-x-1.5 text-xs font-bold text-slate-600 hover:text-slate-900 bg-white border border-slate-200 px-3 py-1.5 rounded-lg shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            <span>Kembali ke Daftar</span>
        </a>

        <div class="flex items-center space-x-2">
            <button type="button" onclick="window.print()" class="px-3.5 py-1.5 text-xs font-bold text-slate-700 bg-white border border-slate-200 rounded-lg shadow-sm hover:bg-slate-50 flex items-center space-x-1.5">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                <span>Cetak / Simpan PDF</span>
            </button>
        </div>
    </div>

    <!-- Candidate Profile Card -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-sm">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 pb-6 border-b border-slate-100">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-blue-600" style="color: var(--color-primary);">Profil Kandidat</span>
                <h2 class="text-2xl font-black text-slate-900 mt-0.5">{{ $submission->candidate->name }}</h2>
                <p class="text-sm font-semibold text-slate-600">{{ $submission->candidate->applied_position }}</p>
            </div>

            <div class="flex flex-wrap gap-2">
                @php
                    $cleanPhone = preg_replace('/[^0-9]/', '', $submission->candidate->whatsapp_number);
                    if (str_starts_with($cleanPhone, '0')) {
                        $cleanPhone = '62' . substr($cleanPhone, 1);
                    }
                @endphp
                <a href="https://wa.me/{{ $cleanPhone }}?text=Halo%20{{ urlencode($submission->candidate->name) }}" target="_blank"
                   class="px-4 py-2 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold shadow flex items-center space-x-1.5">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.181-.076.355.101.173.449.741.964 1.201.662.591 1.221.774 1.394.86s.275.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.043.073.043.419-.101.824z"/></svg>
                    <span>Hubungi WhatsApp</span>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 pt-6 text-xs">
            <div>
                <span class="text-slate-400 font-medium block">Nomor WhatsApp</span>
                <strong class="text-slate-800 text-sm mt-0.5 block">{{ $submission->candidate->whatsapp_number }}</strong>
            </div>
            <div>
                <span class="text-slate-400 font-medium block">Platform Lowongan</span>
                <strong class="text-slate-800 text-sm mt-0.5 block">{{ $submission->candidate->source_platform }}</strong>
            </div>
            <div>
                <span class="text-slate-400 font-medium block">Durasi Pengerjaan</span>
                <strong class="text-slate-800 text-sm mt-0.5 block">
                    @if($submission->submitted_at)
                        {{ $submission->started_at->diffInMinutes($submission->submitted_at) }} Menit {{ $submission->started_at->diffInSeconds($submission->submitted_at) % 60 }} Detik
                    @else
                        -
                    @endif
                </strong>
            </div>
            <div>
                <span class="text-slate-400 font-medium block">Status Pengerjaan</span>
                <div class="mt-0.5">
                    @if($submission->is_time_out)
                        <span class="px-2.5 py-1 rounded bg-red-100 text-red-700 font-extrabold text-xs">Waktu Habis (Timeout)</span>
                    @else
                        <span class="px-2.5 py-1 rounded bg-emerald-100 text-emerald-700 font-extrabold text-xs">Selesai Tepat Waktu</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- DISC Personality Summary Card -->
    @if(!empty($discScores['profile_name']))
        <div class="bg-gradient-to-r from-blue-900 to-slate-900 rounded-2xl text-white p-6 sm:p-8 shadow-md">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-4 border-b border-white/10">
                <div>
                    <span class="text-[11px] font-bold uppercase tracking-widest text-blue-300">Hasil Analisis Profil Perilaku</span>
                    <h3 class="text-2xl font-black mt-1">{{ $discScores['profile_name'] }} ({{ $discScores['profile_code'] }})</h3>
                </div>
                <div class="px-4 py-2 rounded-xl bg-white/10 backdrop-blur-md border border-white/20 text-center">
                    <span class="text-[10px] uppercase tracking-wider text-slate-300 block">Tipe Utama</span>
                    <span class="text-lg font-black text-amber-400">{{ $discScores['primary_dimension'] }} / {{ $discScores['secondary_dimension'] }}</span>
                </div>
            </div>

            <p class="text-sm text-slate-200 mt-4 leading-relaxed font-medium">
                {{ $discScores['summary'] }}
            </p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6 pt-6 border-t border-white/10 text-xs">
                <div>
                    <h4 class="font-bold text-amber-300 mb-2 flex items-center space-x-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span>Kekuatan Karakteristik Perilaku:</span>
                    </h4>
                    <ul class="list-disc list-inside space-y-1 text-slate-300">
                        @foreach($discScores['strengths'] ?? [] as $strength)
                            <li>{{ $strength }}</li>
                        @endforeach
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-blue-300 mb-2 flex items-center space-x-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        <span>Lingkungan Kerja yang Ideal:</span>
                    </h4>
                    <p class="text-slate-300 leading-relaxed">{{ $discScores['work_environment'] ?? '-' }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- 3 DISC Line Charts Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Grafik 1: Mask / Most -->
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
            <div class="border-b border-slate-100 pb-3 mb-2">
                <span class="px-2 py-0.5 rounded bg-blue-100 text-blue-800 font-extrabold text-[10px] uppercase">Grafik 1: Mask (Most)</span>
                <h4 class="text-sm font-bold text-slate-900 mt-1">Perilaku Publik / Adaptasi Kerja</h4>
                <p class="text-[11px] text-slate-500">Karakter yang ditampilkan di lingkungan profesional.</p>
            </div>
            <div id="chartMask" class="h-64"></div>
            <div class="grid grid-cols-4 gap-1 text-center text-xs pt-2 border-t border-slate-100 font-bold">
                <div><span class="text-red-500">D:</span> {{ $discScores['graph_1_mask']['D'] ?? 0 }}</div>
                <div><span class="text-amber-500">I:</span> {{ $discScores['graph_1_mask']['I'] ?? 0 }}</div>
                <div><span class="text-emerald-500">S:</span> {{ $discScores['graph_1_mask']['S'] ?? 0 }}</div>
                <div><span class="text-blue-500">C:</span> {{ $discScores['graph_1_mask']['C'] ?? 0 }}</div>
            </div>
        </div>

        <!-- Grafik 2: Core / Least -->
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
            <div class="border-b border-slate-100 pb-3 mb-2">
                <span class="px-2 py-0.5 rounded bg-amber-100 text-amber-800 font-extrabold text-[10px] uppercase">Grafik 2: Core (Least)</span>
                <h4 class="text-sm font-bold text-slate-900 mt-1">Perilaku Dasar / Di Bawah Tekanan</h4>
                <p class="text-[11px] text-slate-500">Karakter alami saat menghadapi stres / konflik.</p>
            </div>
            <div id="chartCore" class="h-64"></div>
            <div class="grid grid-cols-4 gap-1 text-center text-xs pt-2 border-t border-slate-100 font-bold">
                <div><span class="text-red-500">D:</span> {{ $discScores['graph_2_core']['D'] ?? 0 }}</div>
                <div><span class="text-amber-500">I:</span> {{ $discScores['graph_2_core']['I'] ?? 0 }}</div>
                <div><span class="text-emerald-500">S:</span> {{ $discScores['graph_2_core']['S'] ?? 0 }}</div>
                <div><span class="text-blue-500">C:</span> {{ $discScores['graph_2_core']['C'] ?? 0 }}</div>
            </div>
        </div>

        <!-- Grafik 3: Mirror / Change -->
        <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
            <div class="border-b border-slate-100 pb-3 mb-2">
                <span class="px-2 py-0.5 rounded bg-purple-100 text-purple-800 font-extrabold text-[10px] uppercase">Grafik 3: Mirror (Perceived)</span>
                <h4 class="text-sm font-bold text-slate-900 mt-1">Pola Kepribadian Terintegrasi</h4>
                <p class="text-[11px] text-slate-500">Selisih perhitungan (Most minus Least).</p>
            </div>
            <div id="chartMirror" class="h-64"></div>
            <div class="grid grid-cols-4 gap-1 text-center text-xs pt-2 border-t border-slate-100 font-bold">
                <div><span class="text-red-500">D:</span> {{ $discScores['graph_3_mirror']['D'] ?? 0 }}</div>
                <div><span class="text-amber-500">I:</span> {{ $discScores['graph_3_mirror']['I'] ?? 0 }}</div>
                <div><span class="text-emerald-500">S:</span> {{ $discScores['graph_3_mirror']['S'] ?? 0 }}</div>
                <div><span class="text-blue-500">C:</span> {{ $discScores['graph_3_mirror']['C'] ?? 0 }}</div>
            </div>
        </div>
    </div>

    <!-- Question Answers Breakdown Section -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-sm">
        <div class="flex items-center justify-between pb-4 mb-6 border-b border-slate-100">
            <div>
                <h3 class="text-lg font-bold text-slate-900">Rincian Jawaban Soal (1 - 24)</h3>
                <p class="text-xs text-slate-500">Pilihan Most (+ M) dan Least (- L) yang dipilih kandidat pada setiap butir soal.</p>
            </div>
            <div class="flex items-center space-x-3 text-xs">
                <span class="flex items-center space-x-1"><span class="w-3 h-3 rounded bg-emerald-500 inline-block"></span> <strong class="text-emerald-700">Most (+ M)</strong></span>
                <span class="flex items-center space-x-1"><span class="w-3 h-3 rounded bg-rose-500 inline-block"></span> <strong class="text-rose-700">Least (- L)</strong></span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($assessment->questions as $question)
                @php
                    $ans = $answersMap[$question->question_number] ?? null;
                    $mostOptId = $ans['most_option_id'] ?? null;
                    $leastOptId = $ans['least_option_id'] ?? null;
                @endphp
                <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/50">
                    <div class="flex items-center justify-between mb-2.5 pb-2 border-b border-slate-200">
                        <span class="text-xs font-extrabold text-slate-700">Nomor #{{ $question->question_number }}</span>
                        @if($ans)
                            <span class="text-[10px] font-bold text-slate-500">
                                M: <strong class="text-emerald-600">{{ $ans['most_disc'] ?? '-' }}</strong> | 
                                L: <strong class="text-rose-600">{{ $ans['least_disc'] ?? '-' }}</strong>
                            </span>
                        @else
                            <span class="text-[10px] text-amber-600 font-bold">Tidak Terjawab</span>
                        @endif
                    </div>

                    <div class="space-y-1.5 text-xs">
                        @foreach($question->options as $opt)
                            @php
                                $isMost = ($opt->id === $mostOptId);
                                $isLeast = ($opt->id === $leastOptId);
                            @endphp
                            <div class="p-2 rounded-lg flex items-center justify-between
                                {{ $isMost ? 'bg-emerald-100 border border-emerald-300 font-bold text-emerald-900' : '' }}
                                {{ $isLeast ? 'bg-rose-100 border border-rose-300 font-bold text-rose-900' : '' }}
                                {{ !$isMost && !$isLeast ? 'bg-white text-slate-600' : '' }}">
                                <span>{{ $opt->option_text }}</span>
                                <div class="flex items-center space-x-1 shrink-0 ml-2">
                                    <span class="text-[10px] font-mono text-slate-400">[{{ $opt->disc_type }}]</span>
                                    @if($isMost)
                                        <span class="px-1.5 py-0.5 rounded bg-emerald-600 text-white font-extrabold text-[10px]">+ Most</span>
                                    @elseif($isLeast)
                                        <span class="px-1.5 py-0.5 rounded bg-rose-600 text-white font-extrabold text-[10px]">- Least</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
        const maskData = @json($graph1Mask);
        const coreData = @json($graph2Core);
        const mirrorData = @json($graph3Mirror);

        const commonChartConfig = {
            chart: {
                type: 'line',
                height: 240,
                toolbar: { show: false },
                animations: { enabled: true }
            },
            stroke: { width: 3, curve: 'straight' },
            markers: { size: 6, strokeWidth: 2 },
            xaxis: {
                categories: ['D', 'I', 'S', 'C'],
                labels: { style: { fontWeight: 800, fontSize: '13px' } }
            },
            yaxis: {
                labels: { style: { fontSize: '10px' } }
            },
            grid: { borderColor: '#f1f5f9' }
        };

        // Render Grafik 1: Mask
        new ApexCharts(document.querySelector("#chartMask"), {
            ...commonChartConfig,
            series: [{ name: 'Skor Most (Mask)', data: [maskData.D || 0, maskData.I || 0, maskData.S || 0, maskData.C || 0] }],
            colors: ['#2563eb'],
            markers: { ...commonChartConfig.markers, strokeColors: '#2563eb' }
        }).render();

        // Render Grafik 2: Core
        new ApexCharts(document.querySelector("#chartCore"), {
            ...commonChartConfig,
            series: [{ name: 'Skor Least (Core)', data: [coreData.D || 0, coreData.I || 0, coreData.S || 0, coreData.C || 0] }],
            colors: ['#f59e0b'],
            markers: { ...commonChartConfig.markers, strokeColors: '#f59e0b' }
        }).render();

        // Render Grafik 3: Mirror
        new ApexCharts(document.querySelector("#chartMirror"), {
            ...commonChartConfig,
            series: [{ name: 'Skor Change (Mirror)', data: [mirrorData.D || 0, mirrorData.I || 0, mirrorData.S || 0, mirrorData.C || 0] }],
            colors: ['#8b5cf6'],
            markers: { ...commonChartConfig.markers, strokeColors: '#8b5cf6' }
        }).render();
    });
</script>
@endpush
