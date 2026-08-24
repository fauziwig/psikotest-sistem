@extends('candidate.layouts.app')

@section('title', 'Tes Selesai - ' . $assessment->title)

@section('content')
<div class="max-w-xl mx-auto px-4 py-12 sm:px-6 w-full my-auto">
    <div class="bg-white rounded-2xl border border-slate-200 p-8 sm:p-10 shadow-lg text-center">
        <!-- Success Icon -->
        <div class="w-16 h-16 rounded-full bg-emerald-100 text-emerald-600 mx-auto flex items-center justify-center mb-6 shadow-inner">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>

        <h2 class="text-2xl font-extrabold text-slate-900 mb-2">Terima Kasih, Tes Selesai!</h2>
        <p class="text-sm text-slate-600 mb-8 leading-relaxed">
            Jawaban assessment Anda telah berhasil tersimpan dalam sistem. Tim HR dan Rekrutmen kami akan meninjau hasil profil kepribadian Anda untuk tahapan proses seleksi berikutnya.
        </p>

        @if($submission)
            <div class="bg-slate-50 border border-slate-200 rounded-xl p-5 mb-8 text-left text-xs space-y-3">
                <div class="flex justify-between border-b border-slate-200 pb-2">
                    <span class="text-slate-500">Nama Kandidat:</span>
                    <strong class="text-slate-800">{{ $submission->candidate->name }}</strong>
                </div>
                <div class="flex justify-between border-b border-slate-200 pb-2">
                    <span class="text-slate-500">Posisi yang Dilamar:</span>
                    <strong class="text-slate-800">{{ $submission->candidate->applied_position }}</strong>
                </div>
                <div class="flex justify-between border-b border-slate-200 pb-2">
                    <span class="text-slate-500">No. WhatsApp:</span>
                    <strong class="text-slate-800">{{ $submission->candidate->whatsapp_number }}</strong>
                </div>
                <div class="flex justify-between border-b border-slate-200 pb-2">
                    <span class="text-slate-500">Waktu Mulai:</span>
                    <span class="text-slate-700">{{ $submission->started_at->translatedFormat('d M Y, H:i:s') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Waktu Selesai:</span>
                    <span class="text-slate-700">{{ $submission->submitted_at ? $submission->submitted_at->translatedFormat('d M Y, H:i:s') : '-' }}</span>
                </div>
            </div>
        @endif

        <div class="text-xs text-slate-400">
            Anda dapat menutup jendela browser ini.
        </div>
    </div>
</div>
@endsection
