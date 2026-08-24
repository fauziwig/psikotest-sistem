@extends('admin.layouts.app')

@section('title', 'Daftar Hasil Kandidat')
@section('page_title', 'Daftar Hasil Assessment Kandidat')

@section('content')
<div class="space-y-5">
    
    <!-- Filter & Search Card -->
    <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
        <form action="{{ route('admin.submissions.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            
            <!-- Search -->
            <div>
                <label for="search" class="block text-xs font-bold text-slate-600 mb-1">Cari Nama / WhatsApp / Posisi</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}"
                       placeholder="Ketik kata kunci pencarian..."
                       class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Platform Filter -->
            <div>
                <label for="platform" class="block text-xs font-bold text-slate-600 mb-1">Platform Lamaran</label>
                <select name="platform" id="platform"
                        class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                    <option value="">-- Semua Platform --</option>
                    @foreach($platforms as $p)
                        <option value="{{ $p }}" {{ request('platform') === $p ? 'selected' : '' }}>{{ $p }}</option>
                    @endforeach
                </select>
            </div>

            <!-- DISC Dimension Filter -->
            <div>
                <label for="disc" class="block text-xs font-bold text-slate-600 mb-1">Dimensi DISC Dominan</label>
                <select name="disc" id="disc"
                        class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                    <option value="">-- Semua Dimensi --</option>
                    <option value="D" {{ request('disc') === 'D' ? 'selected' : '' }}>D - Dominance</option>
                    <option value="I" {{ request('disc') === 'I' ? 'selected' : '' }}>I - Influence</option>
                    <option value="S" {{ request('disc') === 'S' ? 'selected' : '' }}>S - Steadiness</option>
                    <option value="C" {{ request('disc') === 'C' ? 'selected' : '' }}>C - Conscientiousness</option>
                </select>
            </div>

            <!-- Actions -->
            <div class="flex items-end space-x-2">
                <button type="submit"
                        class="flex-1 py-2 px-3 text-xs font-bold text-white rounded-lg shadow-sm hover:opacity-90 transition"
                        style="background-color: var(--color-primary);">
                    Filter Data
                </button>
                <a href="{{ route('admin.submissions.index') }}"
                   class="py-2 px-3 text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-bold text-slate-900">
                Total Hasil: <span class="text-blue-600">{{ $submissions->total() }}</span> Kandidat
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-[11px] uppercase tracking-wider text-slate-500 font-bold border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">Nama Lengkap</th>
                        <th class="py-3 px-4">Nomor WhatsApp</th>
                        <th class="py-3 px-4">Posisi Dilamar</th>
                        <th class="py-3 px-4">Sumber Lowongan</th>
                        <th class="py-3 px-4">Profil DISC</th>
                        <th class="py-3 px-4">Waktu Pengerjaan</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-medium">
                    @forelse($submissions as $sub)
                        @php
                            $cleanPhone = preg_replace('/[^0-9]/', '', $sub->candidate->whatsapp_number);
                            if (str_starts_with($cleanPhone, '0')) {
                                $cleanPhone = '62' . substr($cleanPhone, 1);
                            }
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition">
                            <!-- Name -->
                            <td class="py-3.5 px-4 font-bold text-slate-900">
                                {{ $sub->candidate->name }}
                            </td>

                            <!-- WhatsApp with Direct Chat -->
                            <td class="py-3.5 px-4">
                                <a href="https://wa.me/{{ $cleanPhone }}?text=Halo%20{{ urlencode($sub->candidate->name) }},%20kami%20dari%20tim%20HR%20rekrutmen"
                                   target="_blank"
                                   class="inline-flex items-center space-x-1 text-emerald-600 hover:text-emerald-700 font-semibold bg-emerald-50 px-2.5 py-1 rounded-md">
                                    <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
                                        <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.006c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.181-.076.355.101.173.449.741.964 1.201.662.591 1.221.774 1.394.86s.275.072.376-.043c.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.043.073.043.419-.101.824z"/>
                                    </svg>
                                    <span>{{ $sub->candidate->whatsapp_number }}</span>
                                </a>
                            </td>

                            <!-- Applied Position -->
                            <td class="py-3.5 px-4 font-semibold text-slate-800">
                                {{ $sub->candidate->applied_position }}
                            </td>

                            <!-- Source Platform -->
                            <td class="py-3.5 px-4">
                                <span class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-700 text-[11px]">
                                    {{ $sub->candidate->source_platform }}
                                </span>
                            </td>

                            <!-- DISC Profile Code -->
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-1 rounded-md text-xs font-black
                                    {{ ($sub->disc_scores['primary_dimension'] ?? '') === 'D' ? 'bg-red-100 text-red-700' : '' }}
                                    {{ ($sub->disc_scores['primary_dimension'] ?? '') === 'I' ? 'bg-amber-100 text-amber-700' : '' }}
                                    {{ ($sub->disc_scores['primary_dimension'] ?? '') === 'S' ? 'bg-emerald-100 text-emerald-700' : '' }}
                                    {{ ($sub->disc_scores['primary_dimension'] ?? '') === 'C' ? 'bg-blue-100 text-blue-700' : '' }}">
                                    {{ $sub->disc_scores['profile_code'] ?? 'N/A' }}
                                </span>
                            </td>

                            <!-- Time Finished -->
                            <td class="py-3.5 px-4 text-slate-500">
                                {{ $sub->submitted_at ? $sub->submitted_at->translatedFormat('d M Y, H:i') : '-' }}
                            </td>

                            <!-- Status Timeout -->
                            <td class="py-3.5 px-4 text-center">
                                @if($sub->is_time_out)
                                    <span class="px-2 py-0.5 rounded bg-red-100 text-red-700 text-[10px] font-bold">Timeout</span>
                                @else
                                    <span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-700 text-[10px] font-bold">Tepat Waktu</span>
                                @endif
                            </td>

                            <!-- Actions -->
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
                            <td colspan="8" class="py-12 text-center text-slate-400">
                                Tidak ada data hasil assessment yang sesuai filter.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($submissions->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $submissions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
