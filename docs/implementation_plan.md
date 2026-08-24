# Implementation Plan: DISC Scoring Engine & Standard Question Bank Seeder

Rencana ini merinci pembuatan **DISC Scoring Engine Service**, **Seeder Bank Soal Standar DISC (24 Nomor / 96 Opsi Pernyataan)**, default company branding & HR admin, serta pengujian otomatisnya.

---

## 1. Spesifikasi Komponen

### A. `DiscScoringService` (`app/Services/DiscScoringService.php`)
Service class untuk memproses dan mengkalkulasi respon jawaban kandidat:
- **Input**:
  - Array jawaban per butir soal: `[{ 'question_number': 1, 'most_disc': 'D', 'least_disc': 'C', 'most_option_id': 1, 'least_option_id': 4 }, ...]`
- **Kalkulasi**:
  1. **Grafik 1 (Mask / Public Profile)**: Menghitung frekuensi *Most* (M) untuk dimensi D, I, S, C.
  2. **Grafik 2 (Core / Private Profile)**: Menghitung frekuensi *Least* (L) untuk dimensi D, I, S, C.
  3. **Grafik 3 (Mirror / Perceived Profile)**: Selisih skor `Most - Least` (D, I, S, C).
  4. **Profile Pattern & Summary**: Menentukan tipe kepribadian dominan (misal: "High D", "D/I - Result Oriented", dsb) dan deskripsi ringkasnya.
- **Validasi**:
  - Memastikan *Most* dan *Least* tidak memilih opsi yang sama pada butir soal yang sama.
  - Memastikan seluruh nomor soal terisi lengkap.

---

### B. Seeder Bank Soal Standar DISC (`database/seeders/DiscAssessmentSeeder.php`)
Membuat data awal standar industri untuk psikotes DISC:
1. **Default Company Setting**:
   - `company_name`: "TalentCorp International"
   - `primary_color`: `#2563eb`
   - `secondary_color`: `#475569`
2. **Default HR User**:
   - `name`: "HR Administrator"
   - `email`: "hr@company.com"
   - `password`: "password123"
   - `role`: "hr"
3. **Assessment Standar**:
   - `title`: "DISC Behavioral Assessment"
   - `slug`: "disc-behavioral-assessment"
   - `duration_minutes`: 15
   - `is_published`: true
4. **24 Paket Butir Soal**:
   - 24 nomor pertanyaan forced-choice.
   - Masing-masing nomor memiliki 4 opsi pernyataan bahasa Indonesia yang merepresentasikan dimensi **D** (Dominance), **I** (Influence), **S** (Steadiness), dan **C** (Compliance).

---

## 2. Rencana File Baru

- [NEW] `app/Services/DiscScoringService.php`
- [NEW] `database/seeders/DiscAssessmentSeeder.php`
- [NEW] `tests/Unit/DiscScoringServiceTest.php`
- [MODIFY] `database/seeders/DatabaseSeeder.php`

---

## 3. Verification Plan

### Automated Tests
```bash
php artisan test --filter=DiscScoringServiceTest
php artisan migrate:fresh --seed --seeder=DiscAssessmentSeeder
php artisan test
```
