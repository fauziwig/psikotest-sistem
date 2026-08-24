<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanySetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AdminBrandingController extends Controller
{
    /**
     * Tampilkan halaman pengaturan branding perusahaan.
     */
    public function index(): View
    {
        $setting = CompanySetting::first() ?? CompanySetting::create([
            'company_name' => 'Nama Perusahaan',
            'primary_color' => '#2563eb',
            'secondary_color' => '#475569',
        ]);

        return view('admin.branding.index', compact('setting'));
    }

    /**
     * Simpan perubahan branding perusahaan.
     */
    public function update(Request $request): RedirectResponse
    {
        $setting = CompanySetting::first() ?? new CompanySetting();

        $validated = $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'primary_color' => ['required', 'string', 'regex:/^#[a-fA-F0-9]{6}$/'],
            'secondary_color' => ['required', 'string', 'regex:/^#[a-fA-F0-9]{6}$/'],
            'logo' => ['nullable', 'image', 'mimes:png,jpg,jpeg,svg,webp', 'max:2048'],
            'favicon' => ['nullable', 'file', 'mimes:ico,png,svg', 'max:1024'],
        ], [
            'company_name.required' => 'Nama perusahaan wajib diisi.',
            'primary_color.regex' => 'Format warna primer harus kode heksadesimal valid (contoh: #2563eb).',
            'secondary_color.regex' => 'Format warna sekunder harus kode heksadesimal valid (contoh: #475569).',
        ]);

        if ($request->hasFile('logo')) {
            // Hapus logo lama jika ada
            if ($setting->logo_path && Storage::disk('public')->exists(str_replace('storage/', '', $setting->logo_path))) {
                Storage::disk('public')->delete(str_replace('storage/', '', $setting->logo_path));
            }
            $logoPath = $request->file('logo')->store('branding', 'public');
            $setting->logo_path = 'storage/' . $logoPath;
        }

        if ($request->hasFile('favicon')) {
            // Hapus favicon lama jika ada
            if ($setting->favicon_path && Storage::disk('public')->exists(str_replace('storage/', '', $setting->favicon_path))) {
                Storage::disk('public')->delete(str_replace('storage/', '', $setting->favicon_path));
            }
            $faviconPath = $request->file('favicon')->store('branding', 'public');
            $setting->favicon_path = 'storage/' . $faviconPath;
        }

        $setting->company_name = $validated['company_name'];
        $setting->primary_color = $validated['primary_color'];
        $setting->secondary_color = $validated['secondary_color'];
        $setting->save();

        return back()->with('success', 'Pengaturan branding perusahaan berhasil disimpan.');
    }
}
