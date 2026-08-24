# Implementation Plan: Comprehensive README.md Documentation

Rencana ini merinci penyusunan file [README.md](file:///home/fadlikadn/Documents/coding/psikotest-sistem/README.md) yang lengkap, profesional, dan mudah dipahami oleh developer maupun tim HR/operasional.

---

## 1. Struktur Konten [README.md](file:///home/fadlikadn/Documents/coding/psikotest-sistem/README.md)

1. **Judul & Deskripsi Proyek**:
   - Ringkasan sistem asesmen psikotes perilaku DISC (*forced-choice Most & Least*).
   - Tujuan bisnis: Memudahkan HR mengelola asesmen mandiri, kandidat mengerjakan via browser, dan hasil langsung terkalkulasi real-time.
2. **Tech Stack & Arsitektur**:
   - Framework: **Laravel 13 (PHP 8.3+)**
   - Database: **SQLite** (Zero configuration)
   - Antarmuka & Interaktivitas: **Blade + Alpine.js + Tailwind CSS**
   - Visualisasi Grafik: **ApexCharts**
3. **Fitur-Fitur Utama**:
   - **Kandidat**: Form data diri 4 kolom (Nama, WhatsApp, Posisi, Platform), countdown timer otomatis, matrix forced-choice interaktif, auto-submit timeout.
   - **HR / Admin**: Dashboard overview statistik, tabel submission dengan pencarian/filter, direct link WhatsApp chat, visualisasi **3 Grafik Profil DISC** (*Mask*, *Core*, *Mirror*), rincian jawaban 24 butir soal, manajemen assessment, dan kustomisasi company branding.
   - **Scoring Engine**: Kalkulasi skor Most, Least, Change, serta pemetaan profil perilaku dominan.
4. **Persyaratan Sistem (Prerequisites)**:
   - PHP >= 8.2 (dengan ekstensi `pdo_sqlite`, `mbstring`, `openssl`, `xml`, `curl`)
   - Composer >= 2.x
5. **Panduan Langkah-demi-Langkah Persiapan & Menjalankan**:
   - Setup file `.env`.
   - Install vendor dependency (`composer install`).
   - Generate Application Key (`php artisan key:generate`).
   - Create symbolic link storage (`php artisan storage:link`).
   - Jalankan migrasi dan seeder bank soal 24 nomor (`php artisan migrate:fresh --seed`).
   - Jalankan local server (`php artisan serve`).
6. **Kredensial Login & Akses URL**:
   - URL HR Portal: `http://127.0.0.1:8000/admin/login`
     - Email: `hr@company.com`
     - Password: `password123`
   - URL Publik Assessment: `http://127.0.0.1:8000/assessment/disc-behavioral-assessment` (atau akses langsung root `http://127.0.0.1:8000/`).
7. **Panduan Pengujian (Testing)**:
   - Eksekusi Unit Test & Feature Test (`php artisan test`).
8. **Struktur Direktori & Referensi Proyek**:
   - [GEMINI.md](file:///home/fadlikadn/Documents/coding/psikotest-sistem/GEMINI.md) — PRD & Spesifikasi Sistem.
   - [docs/implementation_plan.md](file:///home/fadlikadn/Documents/coding/psikotest-sistem/docs/implementation_plan.md) — Technical Blueprint.
   - [docs/testing.md](file:///home/fadlikadn/Documents/coding/psikotest-sistem/docs/testing.md) — Matriks Pengujian.
