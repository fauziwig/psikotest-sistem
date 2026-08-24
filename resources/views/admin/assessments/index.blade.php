@extends('admin.layouts.app')

@section('title', 'Kelola Assessment')
@section('page_title', 'Kelola Paket Assessment')

@section('content')
<div class="space-y-6">

    <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm">
        <h3 class="text-base font-bold text-slate-900 mb-1">Paket Assessment Aktif</h3>
        <p class="text-xs text-slate-500 mb-6">Atur durasi waktu pengerjaan, status publikasi, dan salin link assessment untuk dibagikan kepada kandidat.</p>

        <div class="space-y-6">
            @foreach($assessments as $assessment)
                @php
                    $publicUrl = route('candidate.register', $assessment->slug);
                @endphp
                <div class="rounded-xl border border-slate-200 p-6 bg-slate-50/50 hover:bg-slate-50 transition" x-data="{ copied: false }">
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 pb-6 border-b border-slate-200">
                        <div>
                            <div class="flex items-center space-x-2.5 mb-1.5">
                                <h4 class="text-lg font-black text-slate-900">{{ $assessment->title }}</h4>
                                @if($assessment->is_published)
                                    <span class="px-2.5 py-0.5 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold flex items-center space-x-1">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500 inline-block animate-pulse"></span>
                                        <span>Aktif & Terpublikasi</span>
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-700 text-xs font-bold">
                                        Draft / Non-aktif
                                    </span>
                                @endif
                            </div>
                            <p class="text-xs text-slate-600 max-w-2xl leading-relaxed">{{ $assessment->description }}</p>
                        </div>

                        <!-- Public Link Copy Button -->
                        <div class="shrink-0">
                            <div class="flex items-center space-x-2">
                                <input type="text" readonly value="{{ $publicUrl }}"
                                       class="px-3 py-2 text-xs rounded-lg border border-slate-300 bg-white text-slate-600 w-64 select-all">
                                <button type="button"
                                        @click="navigator.clipboard.writeText('{{ $publicUrl }}'); copied = true; setTimeout(() => copied = false, 2000)"
                                        class="px-4 py-2 text-xs font-bold text-white rounded-lg shadow transition flex items-center space-x-1.5"
                                        style="background-color: var(--color-primary);">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                    <span x-text="copied ? 'Tersalin!' : 'Salin Link'"></span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Details & Edit Form -->
                    <div class="pt-6">
                        <form action="{{ route('admin.assessments.update', $assessment->id) }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                            @csrf
                            @method('PUT')

                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-slate-700 mb-1">Judul Assessment</label>
                                <input type="text" name="title" value="{{ old('title', $assessment->title) }}" required
                                       class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Durasi Timer (Menit)</label>
                                <input type="number" name="duration_minutes" value="{{ old('duration_minutes', $assessment->duration_minutes) }}" required min="1" max="180"
                                       class="w-full px-3 py-2 text-xs rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                            </div>

                            <div class="flex items-center space-x-4">
                                <label class="flex items-center space-x-2 text-xs font-bold text-slate-700 cursor-pointer pt-4">
                                    <input type="checkbox" name="is_published" value="1" {{ $assessment->is_published ? 'checked' : '' }}
                                           class="w-4 h-4 rounded text-blue-600 focus:ring-blue-500">
                                    <span>Publikasikan</span>
                                </label>

                                <button type="submit"
                                        class="py-2 px-4 text-xs font-bold text-white rounded-lg shadow-sm hover:opacity-90 transition mt-auto"
                                        style="background-color: var(--color-primary);">
                                    Simpan
                                </button>
                            </div>
                        </form>

                        <div class="flex items-center space-x-6 mt-4 pt-4 border-t border-slate-200/60 text-xs text-slate-500">
                            <span>Total Butir Soal: <strong class="text-slate-800">{{ $assessment->questions_count }} Soal</strong></span>
                            <span>Total Pengerjaan: <strong class="text-slate-800">{{ $assessment->submissions_count }} Submission</strong></span>
                            <span>Slug URL: <code class="px-1.5 py-0.5 rounded bg-slate-200 text-slate-800 font-mono text-[11px]">{{ $assessment->slug }}</code></span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
