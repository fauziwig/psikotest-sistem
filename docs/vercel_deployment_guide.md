# Panduan Lengkap Deployment Psikotest Sistem ke Vercel & Supabase

Dokumen ini adalah panduan resmi langkah demi langkah (*step-by-step guide*) untuk men-deploy aplikasi **Psikotest Sistem (Laravel)** ke **Vercel Serverless Platform** menggunakan database **Supabase (PostgreSQL)**.

---

## Ringkasan Arsitektur

```mermaid
graph LR
    User[Kandidat / HR Admin] -->|HTTPS| Vercel[Vercel Serverless<br/>vercel-php runtime]
    Vercel -->|TCP Port 5432 / SSL| Supa[(Supabase Cloud DB<br/>PostgreSQL)]
    Vercel -->|Read-Write| Tmp[/tmp/ Cache & Views]
```

---

## 1. Status Database Supabase (Telah Selesai)

Database Supabase Anda telah berhasil dimigrasikan dan diisi data seeder awal:
- **Host**: `db.bhrgctcefyfyatgdjtuu.supabase.co`
- **Port**: `5432`
- **Database**: `postgres`
- **User**: `postgres`
- **Tabel**: 9 tabel terbuat (`users`, `company_settings`, `assessments`, `questions`, `question_options`, `candidates`, `candidate_submissions`, dll).
- **Data Awal**: 24 butir soal DISC standar dan akun HR Admin (`hr@company.com` / `password123`) sudah siap digunakan.

---

## 2. Berkas Konfigurasi Serverless yang Telah Disiapkan

Aplikasi ini sudah dilengkapi berkas konfigurasi serverless:
1. **`api/index.php`**: Entrypoint serverless function untuk meneruskan request ke Laravel front-controller.
2. **`vercel.json`**: Konfigurasi routing, runtime `vercel-php@0.7.3`, dan mapping path compiled views & cache ke `/tmp/`.
3. **`.vercelignore`**: Mencegah berkas-berkas lokal (seperti `.git`, `tests`, SQLite) ikut ter-upload ke Vercel.
4. **`bootstrap/app.php`**: Penanganan otomatis pembuatan folder `/tmp/views` saat serverless function pertama kali berjalan.

---

## 3. Langkah Demi Langkah Deploy ke Vercel

### Langkah 1: Push Perubahan ke GitHub
Pastikan seluruh perubahan terbaru telah di-push ke repository GitHub Anda:
```bash
git add .
git commit -m "chore: setup vercel serverless configuration and deployment guide"
git push origin main
```

---

### Langkah 2: Buka Vercel & Import Repository
1. Kunjungi [https://vercel.com](https://vercel.com) dan login/daftar menggunakan akun GitHub Anda.
2. Di dashboard Vercel, klik tombol **"Add New..."** $\rightarrow$ pilih **"Project"**.
3. Cari repository GitHub project `psikotest-sistem` Anda, lalu klik **"Import"**.
4. Pada dropdown **Framework Preset**, pilih **`Other`** (atau biarkan default jika sudah otomatis `Other`).
5. Pada bagian **Build and Output Settings**, biarkan semua toggle dalam keadaan default / kosong (karena konfigurasi sudah ditangani oleh `vercel.json`).

---

### Langkah 3: Konfigurasi Environment Variables di Vercel
Pada halaman **Configure Project** sebelum menekan tombol Deploy, buka menu **Environment Variables** dan masukkan variabel-variabel berikut:

| Key | Value | Keterangan |
| :--- | :--- | :--- |
| `APP_NAME` | `Psikotest Sistem` | Nama aplikasi |
| `APP_ENV` | `production` | Environment aplikasi |
| `APP_KEY` | `base64:i5j8QPnPmlwZQjZLsOhNni+KDNPE5D7JaNgz6+jeGLk=` | Encryption key Laravel Anda |
| `APP_DEBUG` | `false` | Nonaktifkan debug mode |
| `APP_URL` | `https://your-project.vercel.app` | Ganti dengan URL domain Vercel Anda |
| `DB_CONNECTION` | `pgsql` | Driver database PostgreSQL |
| `DB_HOST` | `db.bhrgctcefyfyatgdjtuu.supabase.co` | Host Supabase Anda |
| `DB_PORT` | `5432` | Port standar Supabase |
| `DB_DATABASE` | `postgres` | Nama database |
| `DB_USERNAME` | `postgres` | Username database |
| `DB_PASSWORD` | `P@sswordKuat2026!` | Password database Supabase Anda |
| `SESSION_DRIVER` | `cookie` | Sesi terenkripsi di browser klien |
| `CACHE_STORE` | `array` | In-memory cache per request |
| `LOG_CHANNEL` | `stderr` | Log langsung tampil di Vercel Functions Log |

---

### Langkah 4: Klik Deploy!
1. Klik tombol **"Deploy"** di bagian bawah.
2. Tunggu proses build & deployment selama ~1 menit.
3. Setelah selesai, Vercel akan memberikan domain publik (contoh: `https://psikotest-sistem.vercel.app`).

---

## 4. Akun & URL Setelah Live di Vercel

- **URL Pengerjaan Tes Kandidat**:
  `https://[project-anda].vercel.app/assessment/disc-behavioral-assessment`
- **URL Dashboard HR / Admin**:
  `https://[project-anda].vercel.app/admin/login`
- **Kredensial Default HR Admin**:
  - **Email**: `hr@company.com`
  - **Password**: `password123`

---

## 5. Tips & Catatan Pemeliharaan

1. **Upload Logo & Favicon di Vercel**:
   - Jika mengubah logo atau favicon di menu **Branding Perusahaan**, pastikan ukuran gambar tidak terlalu besar (< 500 KB).
2. **Koneksi Database Supabase**:
   - Jika traffic kandidat sangat tinggi (ratusan kandidat bersamaan), Anda dapat mengganti port di Vercel Environment Variables menjadi **`DB_PORT=6543`** (Transaction Pooler Supabase) untuk efisiensi koneksi pooling.
3. **Melihat Log Error**:
   - Jika terjadi kendala pada runtime Vercel, buka menu **Deployments > [Pilih Deployment] > Logs** di dashboard Vercel untuk membaca output error secara real-time.
