@extends('candidate.layouts.app')

@section('title', $assessment->title)

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8 sm:px-6 lg:px-8">
    <!-- Header Card -->
    <div class="bg-white rounded-xl border border-slate-200 p-6 sm:p-8 shadow-sm mb-6">
        <div class="flex items-center space-x-2 text-xs font-semibold uppercase tracking-wider text-blue-600 mb-2" style="color: var(--color-primary);">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <span>Candidate Assessment</span>
        </div>

        <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mb-3">{{ $assessment->title }}</h2>
        <p class="text-slate-600 text-sm leading-relaxed mb-6">{{ $assessment->description }}</p>

        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 pt-4 border-t border-slate-100 text-sm">
            <div class="flex items-center space-x-2 text-slate-700">
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>Durasi: <strong>{{ $assessment->duration_minutes }} Menit</strong></span>
            </div>
            <div class="flex items-center space-x-2 text-slate-700">
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                <span>Jumlah: <strong>{{ $assessment->questions_count ?? 24 }} Soal</strong></span>
            </div>
            <div class="flex items-center space-x-2 text-slate-700 col-span-2 sm:col-span-1">
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>Format: <strong>Forced-Choice</strong></span>
            </div>
        </div>
    </div>

    <!-- Alert / Messages -->
    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-md mb-6 text-sm text-red-700">
            {{ session('error') }}
        </div>
    @endif

    <!-- Registration Form Card -->
    <div class="bg-white rounded-xl border border-slate-200 p-6 sm:p-8 shadow-sm">
        <h3 class="text-lg font-bold text-slate-900 mb-1">Data Diri Kandidat</h3>
        <p class="text-xs text-slate-500 mb-6">Mohon lengkapi formulir di bawah ini dengan benar sebelum memulai pengerjaan tes.</p>

        <form action="{{ route('candidate.start', $assessment->slug) }}" method="POST" class="space-y-5">
            @csrf

            <!-- 1. Nama Lengkap -->
            <div>
                <label for="name" class="block text-sm font-semibold text-slate-700 mb-1">
                    Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                       placeholder="Contoh: Budi Santoso"
                       class="w-full px-4 py-2.5 text-sm rounded-lg border @error('name') border-red-500 bg-red-50 @else border-slate-300 @enderror focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                @error('name')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- 2. Nomor WhatsApp -->
            <div>
                <label for="whatsapp_number" class="block text-sm font-semibold text-slate-700 mb-1">
                    Nomor WhatsApp <span class="text-red-500">*</span>
                </label>
                <input type="tel" name="whatsapp_number" id="whatsapp_number" value="{{ old('whatsapp_number') }}" required
                       placeholder="Nomor aktif selama sesi rekrutmen (contoh: 081234567890)"
                       class="w-full px-4 py-2.5 text-sm rounded-lg border @error('whatsapp_number') border-red-500 bg-red-50 @else border-slate-300 @enderror focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <p class="text-xs text-slate-400 mt-1">Gunakan nomor WhatsApp yang aktif untuk keperluan konfirmasi hasil.</p>
                @error('whatsapp_number')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- 3. Posisi yang Dilamar -->
            <div>
                <label for="applied_position" class="block text-sm font-semibold text-slate-700 mb-1">
                    Posisi yang Dilamar <span class="text-red-500">*</span>
                </label>
                <input type="text" name="applied_position" id="applied_position" value="{{ old('applied_position') }}" required
                       placeholder="Contoh: Admin Operasional / Frontend Developer"
                       class="w-full px-4 py-2.5 text-sm rounded-lg border @error('applied_position') border-red-500 bg-red-50 @else border-slate-300 @enderror focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <p class="text-xs text-slate-400 mt-1">Isikan sama persis dengan nama lowongan pekerjaan yang sedang diproses.</p>
                @error('applied_position')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- 4. Platform Lamaran Kerja -->
            <div x-data="{ platform: '{{ old('source_platform', '') }}', customPlatform: '' }">
                <label for="source_platform_select" class="block text-sm font-semibold text-slate-700 mb-1">
                    Platform Lamaran Kerja yang Digunakan <span class="text-red-500">*</span>
                </label>
                <select id="source_platform_select" x-model="platform" required
                        class="w-full px-4 py-2.5 text-sm rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white">
                    <option value="">-- Pilih Platform Info Lowongan --</option>
                    <option value="Glints">Glints</option>
                    <option value="Pintarnya.com">Pintarnya.com</option>
                    <option value="Jobstreet">Jobstreet</option>
                    <option value="LinkedIn">LinkedIn</option>
                    <option value="KitaLulus">KitaLulus</option>
                    <option value="Kalibrr">Kalibrr</option>
                    <option value="Referral / Rekomendasi">Referral / Rekomendasi</option>
                    <option value="Website Perusahaan">Website Karir Perusahaan</option>
                    <option value="Lainnya">Lainnya (Tulis Manual)</option>
                </select>

                <!-- Input jika memilih 'Lainnya' -->
                <div x-show="platform === 'Lainnya'" x-cloak class="mt-2">
                    <input type="text" x-model="customPlatform"
                           placeholder="Sebutkan platform lainnya (contoh: Instagram, WhatsApp Group, dll)"
                           class="w-full px-4 py-2 text-sm rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Hidden input dikirim ke backend -->
                <input type="hidden" name="source_platform" :value="platform === 'Lainnya' ? customPlatform : platform">
                @error('source_platform')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Instructions Box -->
            <div class="bg-blue-50 border border-blue-100 rounded-lg p-4 text-xs text-blue-900 space-y-2 mt-6">
                <p class="font-bold flex items-center space-x-1.5 text-sm">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Petunjuk Pengerjaan Tes DISC:</span>
                </p>
                <ul class="list-disc list-inside space-y-1 text-slate-700 pl-1">
                    <li>Setiap nomor soal memiliki 4 pernyataan karakteristik perilaku.</li>
                    <li>Pilih <strong>1 pernyataan yang PALING menggambarkan</strong> diri Anda (<strong>Most / M / +</strong>).</li>
                    <li>Pilih <strong>1 pernyataan yang PALING TIDAK menggambarkan</strong> diri Anda (<strong>Least / L / -</strong>).</li>
                    <li>Anda tidak dapat memilih <em>Most</em> dan <em>Least</em> pada baris pernyataan yang sama.</li>
                    <li>Kerjakan secara spontan dan jujur. Tidak ada jawaban benar maupun salah.</li>
                    <li>Tes akan otomatis tersubmit ketika timer waktu pengerjaan habis.</li>
                </ul>
            </div>

            <!-- Action Button -->
            <div class="pt-4">
                <button type="submit"
                        class="w-full py-3 px-6 text-white font-bold text-base rounded-lg shadow-md hover:opacity-90 transition duration-150 flex items-center justify-center space-x-2"
                        style="background-color: var(--color-primary);">
                    <span>Mulai Kerjakan Tes</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
