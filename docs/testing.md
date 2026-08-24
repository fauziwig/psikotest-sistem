# Testing & Verification Plan: Psikotest Sistem (DISC Assessment)

Dokumen ini berisi skenario pengujian, rencana test otomatis (*automated testing* via PHPUnit/Pest), dan langkah pengujian manual (*manual verification*) untuk memastikan setiap komponen sistem assessment DISC berjalan sesuai spesifikasi di [GEMINI.md](file:///home/fadlikadn/Documents/coding/psikotest-sistem/GEMINI.md).

---

## 1. Lingkup Pengujian (Test Scope)

| Modul | Target Pengujian | Jenis Test |
| :--- | :--- | :--- |
| **Database & Migrations** | Validitas skema SQLite, foreign keys, cascade on delete, integritas relasi Eloquent. | Automated (Artisan & Unit Test) |
| **DISC Scoring Engine** | Akurasi konversi jawaban Most/Least menjadi skor D, I, S, C dan formula 3 Grafik (*Mask*, *Core*, *Mirror*). | Automated (Unit Test PHPUnit) |
| **Registrasi Kandidat** | Validasi input form (Nama, No WhatsApp, Posisi, Platform Lamaran) dan pembuatan sesi tes. | Automated & Manual |
| **Candidate Test Runner** | Validasi forced-choice (1 Most & 1 Least per nomor), countdown timer, auto-submit saat timeout. | Feature & Browser Test |
| **Admin Assessment Builder** | CRUD assessment, tambah/ubah butir soal dan opsi DISC, toggle status publish, link generator. | Feature Test & Manual |
| **Review Hasil & Visualisasi** | Kalkulasi real-time saat submit, rendering 3 grafik profil DISC, dan rincian jawaban per butir soal. | Feature Test & Manual |
| **Company Branding** | Tampilan dinamis logo, favicon, nama perusahaan, serta warna primer/sekunder pada halaman kandidat. | Visual / Manual Test |

---

## 2. Rencana Pengujian Otomatis (Automated Tests)

### A. Test Database & Model Relationships
- **File**: `tests/Unit/DatabaseSchemaTest.php`
- **Skenario**:
  1. Memastikan seluruh migrasi berhasil dieksekusi tanpa error (`migrate:fresh`).
  2. Memastikan relasi `Assessment -> Questions -> QuestionOptions` berelasi dengan benar dan cascade delete berfungsi.
  3. Memastikan relasi `Candidate -> CandidateSubmissions -> Assessment` berelasi dengan benar.

### B. Test DISC Scoring Engine
- **File**: `tests/Unit/DiscCalculatorServiceTest.php`
- **Skenario**:
  1. **Most Count**: Menghitung total pilihan *Most* untuk tiap dimensi (D, I, S, C).
  2. **Least Count**: Menghitung total pilihan *Least* untuk tiap dimensi (D, I, S, C).
  3. **Change / Mirror Score**: Menghitung selisih skor (*Most minus Least*).
  4. **Validation Edge Cases**: Memastikan error handling jika ada nomor soal yang belum diisi atau format payload jawaban tidak valid.

### C. Test Candidate Flow & Submissions
- **File**: `tests/Feature/CandidateAssessmentTest.php`
- **Skenario**:
  1. Kandidat mengakses link publik assessment yang aktif (`is_published = true`) -> `HTTP 200`.
  2. Kandidat mencoba mengakses assessment yang belum dipublish (`is_published = false`) -> `HTTP 404 / 403`.
  3. Validasi form registrasi data diri:
     - Error jika Nama / No WhatsApp / Posisi / Platform kosong.
     - Sukses jika data valid -> redirect ke halaman pengerjaan soal.
  4. Submit jawaban:
     - Skor DISC otomatis terkalkulasi dan tersimpan di kolom `disc_scores`.
     - Timestamp `started_at` dan `submitted_at` tercatat dengan presisi.
     - Flag `is_time_out` bernilai `true` jika waktu pengerjaan melebihi durasi batas.

---

## 3. Skenario Pengujian Manual (Manual Verification Checklist)

### Checklist HR / Admin
- [ ] Login ke dashboard admin HR.
- [ ] Ubah branding perusahaan: Upload logo baru, ganti warna primer (misal: `#10b981`), dan simpan.
- [ ] Buat paket assessment baru dengan durasi 15 menit.
- [ ] Tambahkan butir soal dengan 4 pilihan pernyataan D, I, S, C.
- [ ] Publish assessment dan salin public link.
- [ ] Buka daftar kandidat dan periksa data pelamar yang baru saja menyelesaikan tes.
- [ ] Buka halaman detail hasil kandidat: Verifikasi tampilan 3 grafik DISC (*Mask*, *Core*, *Mirror*) dan detail jawaban per soal.

### Checklist Kandidat (Candidate Experience)
- [ ] Buka public link assessment di Google Chrome.
- [ ] Verifikasi logo, nama perusahaan, dan warna primer sesuai branding yang diatur HR.
- [ ] Isi form data diri:
  - Nama Lengkap
  - Nomor WhatsApp
  - Posisi yang dilamar
  - Platform lamaran kerja (pilih Glints / Pintarnya.com / lainnya)
- [ ] Tekan tombol *Mulai Tes* dan pastikan timer countdown berjalan mundur.
- [ ] Pilih 1 opsi *Most* (M) dan 1 opsi *Least* (L) pada setiap butir soal.
- [ ] Pastikan sistem menolak pemilihan *Most* dan *Least* pada pernyataan yang sama di satu nomor soal.
- [ ] Selesaikan tes dan tekan *Submit*.
- [ ] Verifikasi munculnya halaman konfirmasi tes selesai.
- [ ] Uji skenario batas waktu habis: Biarkan timer mencapai 00:00 dan pastikan sistem melakukan auto-submit.

---

## 4. Perintah Eksekusi Testing

```bash
# Menjalankan seluruh unit & feature test
php artisan test

# Menjalankan test spesifik untuk kalkulasi DISC
php artisan test --filter=DiscCalculatorServiceTest

# Menjalankan test database migrasi
php artisan migrate:fresh --seed
```
