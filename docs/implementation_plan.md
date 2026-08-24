# Implementation Plan: Candidate Test Flow (Registration, Runner, Timer, & Submission)

Rencana ini merinci implementasi modul **Candidate Test Flow** agar kandidat dapat membuka link publik assessment, mendaftarkan data diri, mengerjakan tes DISC dengan timer, dan mengirimkan jawaban untuk dikalkulasi otomatis.

---

## 1. Alur Pengguna (Candidate Flow)

1. **Akses Link Assessment**: Kandidat membuka `/assessment/{slug}`.
2. **Formulir Data Diri (4 Kolom)**:
   - Nama Lengkap
   - Nomor WhatsApp
   - Posisi yang Dilamar
   - Platform Lamaran Kerja (*Glints, Pintarnya.com, Jobstreet, LinkedIn, Referral, Lainnya*)
3. **Instruksi & Mulai**: Kandidat menekan tombol *Mulai Tes*, sistem mencatat `started_at` dan mengalihkan ke halaman pengerjaan soal.
4. **Halaman Test Runner (Alpine.js)**:
   - Countdown timer interaktif berbasis timestamp server.
   - Grid nomor soal 1-24 dengan indikator visual kelengkapan.
   - Matrix forced-choice (*Most* & *Least* mutually exclusive).
   - Auto-submit jika waktu habis.
5. **Submit & Kalkulasi Skor**:
   - Backend memvalidasi integritas jawaban dan waktu pengerjaan.
   - `DiscScoringService` mengkalkulasi 3 grafik DISC (*Mask*, *Core*, *Mirror*) dan tipe kepribadian.
   - Menyimpan hasil ke tabel `candidate_submissions`.
6. **Halaman Selesai**: Pesan konfirmasi dan ringkasan durasi pengerjaan.

---

## 2. File yang Dibuat & Dimodifikasi

- `app/Http/Controllers/CandidateAssessmentController.php`
- `resources/views/candidate/layouts/app.blade.php`
- `resources/views/candidate/register.blade.php`
- `resources/views/candidate/runner.blade.php`
- `resources/views/candidate/completed.blade.php`
- `routes/web.php`
- `tests/Feature/CandidateAssessmentFlowTest.php`

---

## 3. Rencana Pengujian

- **Automated Test**: `tests/Feature/CandidateAssessmentFlowTest.php` (Menguji alur registrasi, validasi form, akses timer, dan penyimpanan skor setelah submit).
- **Manual Verification**: Membuka halaman di browser dan mencoba alur pengerjaan tes secara langsung.
