# System Specification & PRD: Psikotest Sistem (DISC Assessment)

## 1. Overview Sistem

Sistem ini adalah aplikasi web internal perusahaan yang dirancang untuk menjalankan assessment kandidat dalam proses rekrutmen.

Pada tahap **MVP (Minimum Viable Product)**, assessment difokuskan pada **DISC-style behavioral assessment** dengan format pertanyaan **forced-choice** (*Most* dan *Least*).

### Kemampuan Utama Sistem:
- **HR/Admin**:
  - Membuat dan mengelola assessment.
  - Menambahkan, mengubah, dan mengatur pertanyaan beserta opsi DISC.
  - Menentukan durasi pengerjaan assessment (timer).
  - Mempublikasikan assessment untuk menghasilkan public assessment link.
  - Melihat daftar kandidat, waktu pengerjaan, detail jawaban, dan hasil kalkulasi profil DISC.
  - Mengatur branding perusahaan (nama perusahaan, logo, favicon, warna primer & sekunder).
- **Kandidat**:
  - Mengakses link assessment publik via browser.
  - Mengisi data diri sebelum tes:
    1. **Nama Lengkap**
    2. **Nomor WhatsApp** (yang dipakai selama sesi rekrutmen)
    3. **Posisi yang Dilamar** (diisi sama persis dengan lowongan yang diproses)
    4. **Platform Lamaran Kerja yang Digunakan** (contoh: Glints, Pintarnya.com, Jobstreet, LinkedIn, Referral, atau input lainnya)
  - Mengerjakan pertanyaan assessment dengan batasan waktu (timer otomatis).
  - Menyimpan jawaban dan kalkulasi otomatis profil DISC saat disubmit.

---

## 2. Product Goals

### Primary Goals

* **G1 — Mempermudah HR membuat assessment**
  HR tidak perlu meminta bantuan developer untuk mengubah database atau source code ketika ingin:
  - Membuat assessment baru;
  - Mengubah pertanyaan;
  - Mengubah durasi pengerjaan;
  - Menambahkan/menghapus pertanyaan;
  - Melakukan publish/unpublish assessment.

* **G2 — Kandidat dapat mengerjakan assessment melalui browser**
  Kandidat dapat mengakses dan menyelesaikan assessment secara lancar menggunakan:
  - Google Chrome;
  - Komputer / laptop kantor;
  - Komputer / laptop pribadi.
  *(MVP tidak membutuhkan instalasi aplikasi desktop atau mobile app).*

* **G3 — Hasil assessment dapat langsung dilihat HR**
  Setelah kandidat menyelesaikan tes (submit), sistem langsung menghitung dan menyimpan hasil secara real-time sehingga HR dapat melihat:
  - Data lengkap kandidat (Nama, No. WhatsApp);
  - Posisi yang dilamar;
  - Sumber/platform lamaran (Glints, Pintarnya.com, LinkedIn, dll.);
  - Waktu mulai, waktu selesai, dan durasi pengerjaan;
  - Hasil kalkulasi DISC (Skor & Pola Grafik D, I, S, C);
  - Detail pilihan jawaban pada setiap butir soal.

* **G4 — Assessment memiliki branding perusahaan**
  Halaman assessment yang diakses oleh kandidat menampilkan identitas visual perusahaan:
  - Nama perusahaan;
  - Logo perusahaan;
  - Favicon / icon browser;
  - Primary color & secondary color (diaplikasikan pada button, header, dan elemen aksen).

---

## 3. Non-Goals MVP

Fitur-fitur berikut **TIDAK** termasuk dalam cakupan MVP dan dapat dipertimbangkan pada versi berikutnya:
- AI proctoring
- Webcam monitoring & screen recording
- Face recognition & browser lockdown
- Plagiarism detection
- ATS (Applicant Tracking System) integration
- Email & WhatsApp automation
- Single Sign-On (SSO)
- Payment gateway / billing
- Advanced psychometric validation engine
- Mobile application (Android / iOS native app)
- Multi-company SaaS architecture (multi-tenancy)
- Complex permission hierarchy / multi-level role access
- AI-generated questions
- AI-generated candidate recommendations

---

## 4. Tech Stack & Arsitektur (Laravel Ecosystem)

Untuk efisiensi dan kecepatan pengembangan MVP internal, sistem menggunakan arsitektur **Laravel**:

| Komponen | Teknologi | Keterangan |
| :--- | :--- | :--- |
| **Backend & Framework** | **Laravel (PHP)** | Fullstack monolith tangguh, aman, dan cepat didevelop. |
| **Admin Panel (HR)** | **Filament v3 / Blade + Livewire** | CRUD assessment, butir soal, tabel submission kandidat, dan filter instan. |
| **Candidate Test Runner** | **Blade / Livewire + Alpine.js** | Halaman interaktif untuk countdown timer, navigasi soal, dan forced-choice selector. |
| **UI & Styling** | **Tailwind CSS** | Styling modern dengan dukungan dynamic branding CSS variables (Primary & Secondary color). |
| **DISC Chart Visualization** | **ApexCharts / Chart.js** | Visualisasi 3 grafik garis profil DISC (Grafik 1: Mask, Grafik 2: Core, Grafik 3: Mirror). |
| **Database** | **SQLite (Primary MVP)** | Zero-configuration (1 file lokal `database/database.sqlite`), mendukung penuh tipe JSON, dan kompatibel untuk migrasi ke MySQL/PostgreSQL di masa depan. |


---

## 5. User Flows

### A. Alur HR / Admin
1. **Login**: HR masuk ke dashboard admin.
2. **Pengaturan Branding**: HR mengunggah logo, favicon, menentukan nama perusahaan dan palet warna (Primary/Secondary).
3. **Pembuatan Assessment**:
   - HR membuat assessment baru (Judul, Deskripsi, Durasi waktu dalam menit).
   - HR menyusun paket pertanyaan DISC (format 4 pernyataan per nomor untuk dipilih *Paling Menggambarkan [Most]* dan *Paling Tidak Menggambarkan [Least]*).
4. **Publishing**: HR mempublikasikan assessment dan menyalin (copy) public link assessment.
5. **Review Hasil**: HR melihat daftar submission kandidat, membuka halaman detail kandidat untuk melihat skor & visualisasi grafik DISC serta rincian jawaban.

### B. Alur Kandidat
1. **Akses Link**: Kandidat membuka link public assessment yang dibagikan HR di browser.
2. **Formulir Data Diri**:
   - Nama Lengkap
   - Nomor WhatsApp yang aktif selama sesi rekrutmen
   - Posisi yang dilamar (sama persis dengan lowongan yang diproses)
   - Platform lamaran kerja yang digunakan (Glints, Pintarnya.com, LinkedIn, dll.)
3. **Instruksi & Mulai**: Kandidat membaca instruksi pengerjaan tes DISC dan menekan tombol *Mulai Tes*.
4. **Pengerjaan Soal**:
   - Timer countdown berjalan di bagian atas layar.
   - Kandidat memilih 1 opsi *Most* (M) dan 1 opsi *Least* (L) untuk setiap nomor pertanyaan.
5. **Submit & Selesai**:
   - Kandidat menekan tombol *Submit* atau otomatis tersubmit jika waktu habis.
   - Kandidat melihat pesan konfirmasi bahwa tes telah berhasil diselesaikan.

---

## 6. Rancangan Skema Database (Database Blueprint)

### Entitas Data Inti

#### 1. `company_settings`
- `id` (PK, string / bigint)
- `company_name` (string)
- `logo_path` (string, nullable)
- `favicon_path` (string, nullable)
- `primary_color` (string, default: `#2563eb`)
- `secondary_color` (string, default: `#475569`)
- `created_at`, `updated_at` (timestamps)

#### 2. `users` (HR / Admin)
- `id` (PK, bigint)
- `name` (string)
- `email` (string, unique)
- `password` (string)
- `role` (string: `admin` / `hr`)
- `created_at`, `updated_at` (timestamps)

#### 3. `assessments`
- `id` (PK, bigint)
- `title` (string)
- `slug` (string, unique)
- `description` (text, nullable)
- `duration_minutes` (integer, default: 15)
- `is_published` (boolean, default: false)
- `created_at`, `updated_at` (timestamps)

#### 4. `questions`
- `id` (PK, bigint)
- `assessment_id` (FK -> `assessments.id`, cascade delete)
- `question_number` (integer)
- `order_index` (integer, default: 0)
- `created_at`, `updated_at` (timestamps)

#### 5. `question_options`
- `id` (PK, bigint)
- `question_id` (FK -> `questions.id`, cascade delete)
- `option_text` (string)
- `disc_type` (enum / string: `'D'`, `'I'`, `'S'`, `'C'`)
- `order_index` (integer, default: 0)

#### 6. `candidates`
- `id` (PK, bigint)
- `name` (string)
- `whatsapp_number` (string) — Nomor WhatsApp aktif selama proses rekrutmen
- `applied_position` (string) — Posisi yang dilamar
- `source_platform` (string) — Glints, Pintarnya.com, LinkedIn, dll.
- `created_at`, `updated_at` (timestamps)

#### 7. `candidate_submissions`
- `id` (PK, bigint)
- `assessment_id` (FK -> `assessments.id`, cascade delete)
- `candidate_id` (FK -> `candidates.id`, cascade delete)
- `started_at` (datetime)
- `submitted_at` (datetime)
- `is_time_out` (boolean, default: false)
- `answers_payload` (json) — Detail pilihan jawaban per nomor soal
- `disc_scores` (json) — Hasil skor Most, Least, Change, serta koordinat grafik D, I, S, C
- `created_at`, `updated_at` (timestamps)

---

## 7. DISC Scoring Engine Logic (Forced-Choice)

Pada format forced-choice standar DISC:
1. Setiap soal memiliki 4 opsi perilaku yang masing-masing merepresentasikan dimensi **D** (Dominance), **I** (Influence), **S** (Steadiness), atau **C** (Compliance).
2. Kandidat memilih:
   - **Most (M / +)**: Opsi yang paling menggambarkan dirinya (+1 poin untuk dimensi terkait pada Grafik Most).
   - **Least (L / -)**: Opsi yang paling tidak menggambarkan dirinya (+1 poin untuk dimensi terkait pada Grafik Least).
3. Sistem menghitung:
   - **Grafik 1 (Mask / Public Profile)**: Distribusi skor *Most* (Adaptasi perilaku di lingkungan kerja).
   - **Grafik 2 (Core / Private Profile)**: Distribusi skor *Least* (Karakter dasar saat di bawah tekanan).
   - **Grafik 3 (Mirror / Perceived Profile)**: Perbedaan skor (*Most minus Least*) untuk melihat respon kepribadian terintegrasi.

