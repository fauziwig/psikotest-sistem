@extends('admin.layouts.app')

@section('title', 'Overview Dashboard')
@section('page_title', 'Dashboard Overview')

@section('content')
<div class="space-y-6">

    <!-- Stat Cards Row -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        <!-- Total Kandidat -->
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Kandidat</p>
                <h3 class="text-2xl font-black text-slate-900 mt-1">{{ number_format($totalCandidates) }}</h3>
                <p class="text-xs text-slate-500 mt-1">Terdaftar dalam sistem</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
        </div>

        <!-- Total Submissions -->
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Tes Diselesaikan</p>
                <h3 class="text-2xl font-black text-slate-900 mt-1">{{ number_format($totalSubmissions) }}</h3>
                <p class="text-xs text-emerald-600 font-medium mt-1">Tersimpan & terkalkulasi</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>

        <!-- Active Assessments -->
        <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Assessment Aktif</p>
                <h3 class="text-2xl font-black text-slate-900 mt-1">{{ number_format($activeAssessments) }}</h3>
                <p class="text-xs text-slate-500 mt-1">Siap dikerjakan publik</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Middle Row: DISC Distribution & Quick Summary -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- DISC Chart Card -->
        <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
            <h3 class="text-base font-bold text-slate-900 mb-1">Distribusi Tipe Kepribadian DISC Kandidat</h3>
            <p class="text-xs text-slate-500 mb-4">Pola dimensi dominan kandidat yang telah menyelesaikan assessment.</p>
            <div id="discDistributionChart" class="h-64 flex items-center justify-center"></div>
        </div>

        <!-- DISC Dimension Legend / Summary -->
        <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm flex flex-col justify-between">
            <div>
                <h3 class="text-base font-bold text-slate-900 mb-3">Dimensi Kepribadian</h3>
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-2.5 rounded-lg bg-red-50 text-red-900">
                        <div class="flex items-center space-x-2">
                            <span class="w-6 h-6 rounded bg-red-500 text-white font-black text-xs flex items-center justify-center">D</span>
                            <span class="text-xs font-bold">Dominance</span>
                        </div>
                        <span class="text-xs font-black">{{ $discCounts['D'] }} Orang</span>
                    </div>

                    <div class="flex items-center justify-between p-2.5 rounded-lg bg-amber-50 text-amber-900">
                        <div class="flex items-center space-x-2">
                            <span class="w-6 h-6 rounded bg-amber-500 text-white font-black text-xs flex items-center justify-center">I</span>
                            <span class="text-xs font-bold">Influence</span>
                        </div>
                        <span class="text-xs font-black">{{ $discCounts['I'] }} Orang</span>
                    </div>

                    <div class="flex items-center justify-between p-2.5 rounded-lg bg-emerald-50 text-emerald-900">
                        <div class="flex items-center space-x-2">
                            <span class="w-6 h-6 rounded bg-emerald-500 text-white font-black text-xs flex items-center justify-center">S</span>
                            <span class="text-xs font-bold">Steadiness</span>
                        </div>
                        <span class="text-xs font-black">{{ $discCounts['S'] }} Orang</span>
                    </div>

                    <div class="flex items-center justify-between p-2.5 rounded-lg bg-blue-50 text-blue-900">
                        <div class="flex items-center space-x-2">
                            <span class="w-6 h-6 rounded bg-blue-500 text-white font-black text-xs flex items-center justify-center">C</span>
                            <span class="text-xs font-bold">Conscientiousness</span>
                        </div>
                        <span class="text-xs font-black">{{ $discCounts['C'] }} Orang</span>
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100">
                <a href="{{ route('admin.submissions.index') }}" class="text-xs font-bold text-blue-600 hover:underline flex items-center justify-between">
                    <span>Lihat Semua Hasil Kandidat</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Submissions Table -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-base font-bold text-slate-900">Submission Terbaru</h3>
                <p class="text-xs text-slate-500">5 pelamar kerja yang baru saja menyelesaikan tes.</p>
            </div>
            <a href="{{ route('admin.submissions.index') }}" class="px-3 py-1.5 rounded-lg border border-slate-200 text-xs font-bold text-slate-700 hover:bg-slate-50 shadow-sm">
                Lihat Semua
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-[11px] uppercase tracking-wider text-slate-500 font-bold border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">Nama Kandidat</th>
                        <th class="py-3 px-4">Posisi</th>
                        <th class="py-3 px-4">Platform</th>
                        <th class="py-3 px-4">Profil DISC</th>
                        <th class="py-3 px-4">Waktu Selesai</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($recentSubmissions as $sub)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-3.5 px-4 font-bold text-slate-900">
                                {{ $sub->candidate->name }}
                                <div class="text-[11px] text-slate-400 font-normal">{{ $sub->candidate->whatsapp_number }}</div>
                            </td>
                            <td class="py-3.5 px-4 font-semibold text-slate-800">{{ $sub->candidate->applied_position }}</td>
                            <td class="py-3.5 px-4">
                                <span class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-700 text-[11px]">{{ $sub->candidate->source_platform }}</span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-1 rounded-md text-xs font-extrabold
                                    {{ ($sub->disc_scores['primary_dimension'] ?? '') === 'D' ? 'bg-red-100 text-red-700' : '' }}
                                    {{ ($sub->disc_scores['primary_dimension'] ?? '') === 'I' ? 'bg-amber-100 text-amber-700' : '' }}
                                    {{ ($sub->disc_scores['primary_dimension'] ?? '') === 'S' ? 'bg-emerald-100 text-emerald-700' : '' }}
                                    {{ ($sub->disc_scores['primary_dimension'] ?? '') === 'C' ? 'bg-blue-100 text-blue-700' : '' }}">
                                    {{ $sub->disc_scores['profile_code'] ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-slate-500">
                                {{ $sub->submitted_at ? $sub->submitted_at->diffForHumans() : '-' }}
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <a href="{{ route('admin.submissions.show', $sub->id) }}"
                                   class="px-3 py-1.5 rounded-lg text-white font-bold text-xs shadow-sm hover:opacity-90 transition"
                                   style="background-color: var(--color-primary);">
                                    Lihat Hasil
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400">Belum ada kandidat yang menyelesaikan assessment.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const discData = @json($discCounts);
        
        const options = {
            series: [{
                name: 'Jumlah Kandidat',
                data: [discData.D || 0, discData.I || 0, discData.S || 0, discData.C || 0]
            }],
            chart: {
                type: 'bar',
                height: 250,
                toolbar: { show: false }
            },
            plotOptions: {
                bar: {
                    borderRadius: 6,
                    distributed: true,
                    columnWidth: '50%',
                }
            },
            colors: ['#ef4444', '#f59e0b', '#10b981', '#3b82f6'],
            xaxis: {
                categories: ['Dominance (D)', 'Influence (I)', 'Steadiness (S)', 'Compliance (C)'],
                labels: {
                    style: { fontSize: '11px', fontWeight: 600 }
                }
            },
            legend: { show: false },
            dataLabels: { enabled: true }
        };

        const chart = new ApexCharts(document.querySelector("#discDistributionChart"), options);
        chart.render();
    });
</script>
@endpush
