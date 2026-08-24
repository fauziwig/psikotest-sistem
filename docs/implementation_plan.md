# Implementation Plan: HR / Admin Dashboard & DISC Visualization

Rencana ini merinci implementasi modul **HR / Admin Dashboard** yang mencakup autentikasi HR, review daftar hasil kandidat, visualisasi 3 grafik profil DISC (ApexCharts/Chart.js), detail rincian jawaban 24 butir soal, manajemen assessment, dan pengaturan branding perusahaan.

---

## 1. Modul & Fitur Utama

1. **Autentikasi HR / Admin**:
   - Route `/admin/login`, `/admin/logout`.
   - Proteksi route admin via middleware `auth`.
2. **Dashboard Overview (`/admin/dashboard`)**:
   - Metrik statistik kandidat & distribusi tipe DISC.
   - Daftar 5 submission terbaru.
3. **Daftar Submission Kandidat (`/admin/submissions`)**:
   - Filter & pencarian nama, posisi, platform sumber lamaran.
   - Status pengerjaan, skor utama, direct chat WA, dan link detail.
4. **Detail Hasil Kandidat & Visualisasi 3 Grafik DISC (`/admin/submissions/{id}`)**:
   - Header profil kandidat & durasi pengerjaan.
   - **Visualisasi 3 Grafik Garis DISC**:
     - *Grafik 1 (Mask / Most)*: Perilaku adaptasi kerja.
     - *Grafik 2 (Core / Least)*: Respon di bawah tekanan.
     - *Grafik 3 (Mirror / Change)*: Integrasi kepribadian.
   - Ringkasan profil, kekuatan, lingkungan kerja ideal.
   - Detail pilihan jawaban 24 nomor soal.
5. **Manajemen Assessment (`/admin/assessments`)**:
   - Pengaturan durasi & toggle status publish.
   - Copy link publik assessment.
6. **Pengaturan Branding Perusahaan (`/admin/branding`)**:
   - Nama perusahaan, upload logo, upload favicon, dan color picker (Warna Primer & Sekunder).

---

## 2. Rencana Pengujian

- **Automated Feature Test**: `tests/Feature/AdminDashboardTest.php` (Menguji login, akses dashboard terproteksi, review detail hasil dengan 3 grafik DISC, update durasi assessment, dan update branding).
- **Manual Verification**: Login sebagai HR dan verifikasi kelengkapan grafik visual serta branding settings.
