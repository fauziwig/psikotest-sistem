# Psikotest Sistem — DISC Behavioral Assessment Web App

Aplikasi web internal perusahaan untuk menjalankan assessment perilaku kerja kandidat dalam proses rekrutmen menggunakan metode **DISC Assessment** (*Dominance, Influence, Steadiness, Conscientiousness*) berbasis format pertanyaan **forced-choice** (*Most* dan *Least*).

Sistem dilengkapi dengan **kalkulasi otomatis 3 Grafik Profil DISC** (*Mask*, *Core*, *Mirror*), dashboard reporting HR yang komprehensif, pengerjaan tes dengan batasan waktu (timer otomatis), serta dukungan **dynamic company branding** (nama perusahaan, logo, favicon, warna primer & sekunder).

---

## 🚀 Tech Stack

| Layer | Teknologi | Keterangan |
| :--- | :--- | :--- |
| **Backend & Framework** | **Laravel 13 (PHP 8.3+)** | Arsitektur monolith yang tangguh, aman, dan modular. |
| **Database** | **SQLite (Primary MVP)** | *Zero-configuration*, 1 file lokal (`database/database.sqlite`), mendukung JSON column. |
| **Candidate Frontend** | **Blade + Alpine.js** | Countdown timer akurat, auto-submit saat timeout, matrix forced-choice interaktif. |
| **HR / Admin Panel** | **Blade + Tailwind CSS** | Dashboard metrik, filter kandidat, cetak PDF, dan manajemen branding. |
| **Data Visualization** | **ApexCharts** | Visualisasi interaktif 3 grafik profil garis DISC (*Mask*, *Core*, *Mirror*) dan grafik distribusi. |
| **Testing** | **PHPUnit / Pest** | 26 automated unit & feature test suites (100% PASS). |

---

## ✨ Fitur-Fitur Utama

### 1. Modul Kandidat (Candidate Experience)
* **Akses Publik Tanpa Login**: Cukup mengakses tautan publik asesmen yang dibagikan oleh HR.
* **Formulir Data Diri (4 Kolom Wajib)**:
  1. *Nama Lengkap*
  2. *Nomor WhatsApp* (yang aktif selama sesi rekrutmen)
  3. *Posisi yang Dilamar* (diisi sama persis dengan lowongan yang diproses)
  4. *Platform Lamaran Kerja* (*Glints, Pintarnya.com, Jobstreet, LinkedIn, Referral, atau input manual*)
* **Petunjuk & Instruksi Pengerjaan**: Penjelasan mekanisme pemilihan pernyataan *Most (+)* dan *Least (-)*.
* **Interactive Test Runner**:
  - Countdown timer tersinkronisasi dengan server (*anti-cheat / auto-submit saat timeout*).
  - Navigator nomor soal 1–24 dengan penanda warna visual soal terisi.
  - Mekanisme pilihan *Most* dan *Least* yang saling mengecualikan (*mutually exclusive* per nomor).
  - Modal konfirmasi kelengkapan sebelum pengiriman akhir.
* **Halaman Konfirmasi Selesai**: Ringkasan data diri dan bukti waktu pengerjaan tes.

### 2. Modul HR / Admin Portal
* **Autentikasi Aman**: Login berbasis session untuk tim HR dan rekrutmen.
* **Dashboard Overview**: Metrik total kandidat, jumlah tes selesai, dan visualisasi diagram batang distribusi profil kepribadian DISC.
* **Tabel Hasil & Pencarian Kandidat**:
  - Filter berdasarkan nama pelamar, nomor WhatsApp, posisi, platform sumber info lowongan, dan dimensi DISC dominan.
  - Tombol **Direct WhatsApp Chat** (`wa.me`) untuk menghubungi kandidat secara instan.
* **Laporan Detail Hasil Kandidat**:
  - **Grafik 1: Mask (Most)** — Karakteristik adaptasi perilaku di lingkungan profesional.
  - **Grafik 2: Core (Least)** — Karakteristik dasar saat menghadapi tekanan/stres.
  - **Grafik 3: Mirror (Change = Most minus Least)** — Pola integrasi kepribadian.
  - Analisis profil dominan (nama tipe, ringkasan, kekuatan perilaku, dan lingkungan kerja ideal).
  - **Rincian Jawaban 24 Butir Soal**: Penanda badge hijau (+ Most) dan badge merah (- Least) pada setiap nomor.
  - Fitur cetak / simpan sebagai PDF.
* **Manajemen Paket Assessment**: Mengatur durasi pengerjaan (menit), toggle status aktif/publikasi, dan salin link assessment publik.
* **Pengaturan Branding Perusahaan**: Mengubah nama perusahaan, unggah logo, unggah favicon, serta pemilihan warna primer & sekunder dengan *Live Preview*.

### 3. DISC Scoring Engine
* Kalkulasi real-time saat jawaban disubmit.
* Perhitungan otomatis frekuensi Most (M), Least (L), dan Change ($M - L$).
* Penentuan profil utama (*Primary & Secondary Dimensions*) beserta deskripsi karakteristik psikologis.

---

## 📋 Persyaratan Sistem (Prerequisites)

Pastikan lingkungan server / komputer lokal Anda telah terinstal:
* **PHP >= 8.2** (Rekomendasi **PHP 8.3+**)
* Ekstensi PHP wajib: `pdo_sqlite`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `curl`, `fileinfo`
* **Composer >= 2.x**

---

## 🛠️ Panduan Instalasi & Menjalankan Proyek

Ikuti langkah-langkah berikut untuk menyiapkan dan menjalankan project dari awal:

### 1. Buka Direktori Proyek
```bash
cd /path/to/psikotest-sistem
```

### 2. Konfigurasi File Environment
Salin template environment `.env.example` menjadi `.env`:
```bash
cp .env.example .env
```

Pastikan konfigurasi database di dalam file `.env` menggunakan SQLite (bawaan default):
```dotenv
DB_CONNECTION=sqlite
# DB_DATABASE tidak perlu diubah, otomatis membaca database/database.sqlite
```

### 3. Install Dependensi PHP via Composer
```bash
composer install
```

### 4. Generate Application Encryption Key
```bash
php artisan key:generate
```

### 5. Buat Symbolic Link Storage (Untuk Upload Logo/Favicon Branding)
```bash
php artisan storage:link
```

### 6. Jalankan Migrasi Database & Seeder Bank Soal
Perintah ini akan membuat database SQLite, tabel-tabel migrasi, akun HR default, pengaturan branding, serta **24 paket butir soal standar psikotes DISC** (96 opsi pernyataan):
```bash
php artisan migrate:fresh --seed
```

### 7. Jalankan Local Development Server
```bash
php artisan serve
```
Aplikasi sekarang aktif dan dapat diakses di: **`http://127.0.0.1:8000`**

---

## 🔑 Kredensial Login & Akses Tautan

### A. Portal HR / Admin Dashboard
* **URL Login**: [`http://127.0.0.1:8000/admin/login`](http://127.0.0.1:8000/admin/login)
* **Email**: `hr@company.com`
* **Password**: `password123`

### B. Halaman Publik Pengerjaan Tes Kandidat
* **URL Assessment**: [`http://127.0.0.1:8000/assessment/disc-behavioral-assessment`](http://127.0.0.1:8000/assessment/disc-behavioral-assessment)
* *(Catatan: Mengakses halaman utama `http://127.0.0.1:8000/` akan otomatis mengarahkan ke link assessment aktif).*

---

## 🧪 Pengujian Otomatis (Automated Testing)

Project ini dilengkapi dengan **26 skenario pengujian otomatis** (Unit Test & Feature Test) dengan coverage 100% pada logika kalkulasi, validasi form, pengerjaan tes kandidat, dan dashboard HR.

Jalankan seluruh test suite menggunakan perintah:
```bash
php artisan test
```

Contoh output yang diharapkan:
```text
   PASS  Tests\Unit\DiscScoringServiceTest
  ✓ can calculate disc scores correctly
  ✓ throws exception when most and least are same option
  ✓ throws exception on empty answers
  ✓ throws exception on invalid disc dimension

   PASS  Tests\Unit\DatabaseSchemaTest
  ✓ can create company settings
  ✓ can create assessment with questions and options
  ✓ can record candidate and submission

   PASS  Tests\Feature\CandidateAssessmentFlowTest
  ✓ root url redirects to published assessment
  ✓ can view candidate registration page
  ✓ returns 404 for unpublished assessment
  ✓ validates required candidate fields
  ✓ can start assessment and redirect to runner
  ✓ can access runner page with active session
  ✓ can submit answers and calculate disc scores

   PASS  Tests\Feature\AdminDashboardTest
  ✓ unauthenticated user is redirected to login
  ✓ can view login page
  ✓ can login with valid credentials
  ✓ cannot login with invalid credentials
  ✓ authenticated user can view dashboard overview
  ✓ can view and filter submissions list
  ✓ can view submission detail with 3 disc charts
  ✓ can update assessment settings
  ✓ can update company branding settings
  ✓ can logout

  Tests:    26 passed (106 assertions)
  Duration: ~1.6s
```

---

## 📁 Struktur Direktori Utama

```text
psikotest-sistem/
├── app/
│   ├── Http/Controllers/
│   │   ├── Admin/
│   │   │   ├── AdminAuthController.php         # Autentikasi HR login/logout
│   │   │   ├── AdminDashboardController.php    # Overview metrik & chart distribusi
│   │   │   ├── AdminSubmissionController.php   # Tabel hasil & detail 3 grafik DISC
│   │   │   ├── AdminAssessmentController.php   # Kelola durasi & status publish
│   │   │   └── AdminBrandingController.php     # Pengaturan logo, favicon, & warna
│   │   └── CandidateAssessmentController.php   # Form registrasi, runner timer, submit
│   ├── Models/
│   │   ├── Assessment.php                      # Model paket asesmen
│   │   ├── Candidate.php                       # Model pelamar kerja
│   │   ├── CandidateSubmission.php             # Model jawaban & skor hasil tes
│   │   ├── CompanySetting.php                  # Model branding perusahaan
│   │   ├── Question.php                        # Model butir soal nomor 1-24
│   │   ├── QuestionOption.php                  # Model opsi pernyataan (D, I, S, C)
│   │   └── User.php                            # Model akun HR / Admin
│   └── Services/
│       └── DiscScoringService.php              # Engine kalkulasi skor & profil DISC
├── database/
│   ├── database.sqlite                         # File database lokal SQLite
│   ├── migrations/                             # 7 tabel migrasi database
│   └── seeders/
│       └── DiscAssessmentSeeder.php            # Seeder 24 soal DISC, branding, & user
├── docs/
│   ├── implementation_plan.md                  # Rencana arsitektur & eksekusi
│   └── testing.md                              # Matriks pengujian menyeluruh
├── resources/views/
│   ├── admin/                                  # Tampilan antarmuka Dashboard HR
│   │   ├── auth/                               # Form login HR
│   │   ├── dashboard/                          # Ringkasan statistik & chart
│   │   ├── submissions/                        # Daftar & detail visualisasi 3 grafik
│   │   ├── assessments/                        # Kelola link & durasi asesmen
│   │   ├── branding/                           # Live preview & pengaturan warna
│   │   └── layouts/app.blade.php               # Layout Admin + ApexCharts
│   └── candidate/                              # Tampilan antarmuka Kandidat
│       ├── register.blade.php                  # Form data diri 4 kolom & instruksi
│       ├── runner.blade.php                    # Runner soal forced-choice + countdown timer
│       ├── completed.blade.php                 # Halaman konfirmasi tes selesai
│       └── layouts/app.blade.php               # Layout publik + dynamic CSS variables
├── routes/
│   └── web.php                                 # Routing publik & proteksi admin
├── tests/
│   ├── Feature/                                # Feature tests (Kandidat & Admin flow)
│   └── Unit/                                   # Unit tests (Scoring engine & DB schema)
├── GEMINI.md                                   # Dokumen PRD & Spesifikasi Sistem
└── README.md                                   # Panduan lengkap dokumentasi proyek
```

---

## 📖 Dokumentasi Terkait
* [GEMINI.md](file:///home/fadlikadn/Documents/coding/psikotest-sistem/GEMINI.md) — Product Requirements Document (PRD) & System Blueprint.
* [docs/implementation_plan.md](file:///home/fadlikadn/Documents/coding/psikotest-sistem/docs/implementation_plan.md) — Spesifikasi rencana implementasi teknis.
* [docs/testing.md](file:///home/fadlikadn/Documents/coding/psikotest-sistem/docs/testing.md) — Rencana pengujian dan checklist verifikasi manual.

---

## 📄 Lisensi
Hak Cipta &copy; 2026. Sistem ini dikembangkan untuk kebutuhan internal assessment perusahaan.
