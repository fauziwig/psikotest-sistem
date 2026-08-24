@extends('admin.layouts.app')

@section('title', 'Pengaturan Branding')
@section('page_title', 'Pengaturan Branding Perusahaan')

@section('content')
<div class="max-w-4xl mx-auto space-y-6" x-data="{
    primaryColor: '{{ $setting->primary_color ?? '#2563eb' }}',
    secondaryColor: '{{ $setting->secondary_color ?? '#475569' }}',
    companyName: '{{ $setting->company_name ?? 'Nama Perusahaan' }}'
}">

    <div class="bg-white rounded-xl border border-slate-200 p-6 sm:p-8 shadow-sm">
        <h3 class="text-base font-bold text-slate-900 mb-1">Identitas Visual Perusahaan</h3>
        <p class="text-xs text-slate-500 mb-6">Sesuaikan nama, logo, favicon, dan palet warna branding yang akan tampil di halaman assessment kandidat.</p>

        <form action="{{ route('admin.branding.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Company Name -->
            <div>
                <label for="company_name" class="block text-xs font-bold text-slate-700 mb-1">Nama Perusahaan <span class="text-red-500">*</span></label>
                <input type="text" name="company_name" id="company_name" x-model="companyName" required
                       class="w-full px-3.5 py-2.5 text-sm rounded-lg border @error('company_name') border-red-500 @else border-slate-300 @enderror focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('company_name')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Color Palette Row -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 pt-2 border-t border-slate-100">
                <!-- Primary Color -->
                <div>
                    <label for="primary_color" class="block text-xs font-bold text-slate-700 mb-1">
                        Warna Primer (Tombol, Header, Aksen) <span class="text-red-500">*</span>
                    </label>
                    <div class="flex items-center space-x-3">
                        <input type="color" name="primary_color" id="primary_color" x-model="primaryColor"
                               class="w-12 h-10 rounded-lg border border-slate-300 cursor-pointer p-1 bg-white">
                        <input type="text" x-model="primaryColor"
                               class="w-32 px-3 py-2 text-xs font-mono font-bold rounded-lg border border-slate-300 uppercase focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    @error('primary_color')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Secondary Color -->
                <div>
                    <label for="secondary_color" class="block text-xs font-bold text-slate-700 mb-1">
                        Warna Sekunder (Elemen Pendukung) <span class="text-red-500">*</span>
                    </label>
                    <div class="flex items-center space-x-3">
                        <input type="color" name="secondary_color" id="secondary_color" x-model="secondaryColor"
                               class="w-12 h-10 rounded-lg border border-slate-300 cursor-pointer p-1 bg-white">
                        <input type="text" x-model="secondaryColor"
                               class="w-32 px-3 py-2 text-xs font-mono font-bold rounded-lg border border-slate-300 uppercase focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    @error('secondary_color')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Logo & Favicon Upload Row -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 pt-2 border-t border-slate-100">
                <!-- Logo -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Logo Perusahaan</label>
                    @if(!empty($setting->logo_path))
                        <div class="mb-3 p-3 rounded-lg border border-slate-200 bg-slate-50 flex items-center justify-between">
                            <img src="{{ asset($setting->logo_path) }}" alt="Logo Saat Ini" class="h-10 w-auto object-contain">
                            <span class="text-[11px] text-slate-500">Logo Aktif</span>
                        </div>
                    @endif
                    <input type="file" name="logo" accept="image/png,image/jpeg,image/svg+xml,image/webp"
                           class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200">
                    <p class="text-[11px] text-slate-400 mt-1">Format: PNG, JPG, SVG, WebP (Maks: 2MB).</p>
                    @error('logo')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Favicon -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Favicon / Browser Icon</label>
                    @if(!empty($setting->favicon_path))
                        <div class="mb-3 p-3 rounded-lg border border-slate-200 bg-slate-50 flex items-center justify-between">
                            <img src="{{ asset($setting->favicon_path) }}" alt="Favicon Saat Ini" class="h-6 w-6 object-contain">
                            <span class="text-[11px] text-slate-500">Favicon Aktif</span>
                        </div>
                    @endif
                    <input type="file" name="favicon" accept=".ico,image/png,image/svg+xml"
                           class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200">
                    <p class="text-[11px] text-slate-400 mt-1">Format: ICO, PNG, SVG (Maks: 1MB).</p>
                    @error('favicon')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Live Preview Card -->
            <div class="p-5 rounded-xl border border-slate-200 bg-slate-50/70 space-y-3">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Live Preview Tampilan Kandidat</span>
                <div class="p-4 rounded-lg bg-white border border-slate-200 shadow-sm flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-lg text-white font-bold text-sm flex items-center justify-center shadow-sm"
                             :style="`background-color: ${primaryColor};`">
                            <span x-text="companyName.charAt(0) || 'A'"></span>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-slate-900" x-text="companyName || 'Nama Perusahaan'"></h4>
                            <p class="text-[11px] text-slate-500">Candidate Recruitment Assessment</p>
                        </div>
                    </div>

                    <button type="button"
                            class="px-4 py-2 text-xs font-bold text-white rounded-lg shadow"
                            :style="`background-color: ${primaryColor};`">
                        Mulai Tes
                    </button>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-2">
                <button type="submit"
                        class="w-full py-3 px-6 text-white font-bold text-sm rounded-lg shadow-md hover:opacity-90 transition duration-150"
                        style="background-color: var(--color-primary);">
                    Simpan Pengaturan Branding
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
