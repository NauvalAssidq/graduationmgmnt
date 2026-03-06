# MODUL DOKUMENTASI SISTEM INFORMASI MANAJEMEN BUKU WISUDA

**Status Dokumen:** RESMI  
**Versi:** 1.0.0  
**Tanggal Pembuatan:** 7 Februari 2026  
**Bahasa:** Bahasa Indonesia (Formal)

---

## 1. PENDAHULUAN

### 1.1 Latar Belakang
Sistem Informasi Manajemen (SIM) Buku Wisuda dirancang sebagai solusi terpadu untuk mendigitalisasi proses pengelolaan data wisudawan dan pencetakan buku wisuda. Sistem ini dikembangkan untuk mengatasi ketidakefisienan dalam pengelolaan data manual, memastikan validitas data alumni, dan mempercepat proses produksi buku wisuda melalui otomatisasi pembuatan dokumen berbasis format PDF.

### 1.2 Tujuan
Tujuan utama dari dokumen ini adalah menyediakan panduan komprehensif, teknis, dan operasional bagi pengelola sistem (Administrator) dan pengembang (Developer). Dokumen ini berfungsi sebagai acuan tunggal kebenaran (*single source of truth*) terkait arsitektur, konfigurasi, dan penggunaan sistem.

### 1.3 Ruang Lingkup
Dokumen ini mencakup:
1.  Spesifikasi teknis dan arsitektur perangkat lunak.
2.  Prosedur instalasi dan konfigurasi lingkungan server.
3.  Panduan penggunaan fitur fungsional untuk Administrator dan Publik.
4.  Standar pengembangan dan pemeliharaan kode sumber.

### 1.4 Definisi Istilah
-   **Wisudawan**: Mahasiswa yang telah menyelesaikan studi dan terdaftar dalam periode wisuda.
-   **Buku Wisuda**: Dokumen resmi dalam format cetak atau digital yang memuat data profil wisudawan.
-   **Template**: Tata letak desain (*layout*) yang digunakan untuk menstandarisasi tampilan halaman buku wisuda.
-   **Administrator**: Pengguna dengan hak akses penuh untuk mengelola data dan konfigurasi sistem.

---

## 2. SPESIFIKASI TEKNIS

Sistem ini dibangun di atas fondasi teknologi modern dengan spesifikasi ketat sebagai berikut:

| Komponen | Spesifikasi / Versi | Keterangan |
| :--- | :--- | :--- |
| **Bahasa Pemrograman** | PHP ≥ 8.2 | Wajib mematuhi standar PSR-12 |
| **Framework Backend** | Laravel 12.x | Arsitektur MVC (Model-View-Controller) |
| **Framework Frontend** | Blade Templates + TailwindCSS v4 | Utility-first CSS framework |
| **Build Tool** | Vite | Bundler aset frontend modern |
| **Basis Data** | SQLite (Default) / MySQL 8.0+ | Kompatibel dengan Eloquent ORM |
| **PDF Engine** | barryvdh/laravel-dompdf | Generasi dokumen PDF sisi server |
| **Otentikasi** | Laravel Sanctum | Manajemen sesi dan API token |

---

## 3. ARSITEKTUR SISTEM

### 3.1 Struktur Direktori Utama
Struktur direktori, harap dipatuhi dan tidak diubah tanpa alasan arsitektural yang kuat:

-   `app/Http/Controllers/Admin`: Berisi logika pengendali untuk modul administrasi (Dashboard, Wisudawan, Buku Wisuda, Arsip).
-   `app/Http/Controllers/Api`: Berisi pengendali API untuk integrasi data eksternal.
-   `app/Models`: Definisi model data Eloquent (`Wisudawan`, `BukuWisuda`, `Template`).
-   `resources/views/admin`: Berkas antarmuka (UI) untuk panel admin.
-   `resources/views/public`: Berkas antarmuka untuk halaman publik (Landing page, Pencarian).
-   `routes/web.php`: Definisi rute aplikasi web.

### 3.2 Alur Data (Data Flow)
1.  **Input Data**: Administrator mengunggah data wisudawan melalui fitur *Import CSV* atau input manual.
2.  **Pemrosesan**: Sistem memvalidasi data dan menyimpannya ke basis data. Relasi dibentuk antara Wisudawan dan Buku Wisuda.
3.  **Penyajian (Output)**:
    -   **Publik**: Data alumni dapat dicari dan buku wisuda dapat diakses secara digital.
    -   **Cetak**: Administrator memilih template dan memicu *Generate PDF* untuk menghasilkan dokumen siap cetak.

---

## 4. PANDUAN INSTALASI DAN KONFIGURASI

**PERINGATAN:** Ikuti langkah-langkah ini secara berurutan dan presisi.

### 4.1 Prasyarat Sistem
Pastikan lingkungan server telah terpasang:
-   PHP 8.2 atau lebih baru (Ekstensi wajib: `dom`, `gd`, `sqlite3` atau `mysql`).
-   Composer 2.x.
-   Node.js (LTS Version) & NPM.

### 4.2 Prosedur Instalasi
Eksekusi perintah berikut melalui terminal (Shell/Bash):

1.  **Kloning Repositori**
    ```bash
    git clone <url-repository>
    cd graduation
    ```

2.  **Instalasi Dependensi PHP**
    ```bash
    composer install --optimize-autoloader --no-dev
    ```
    *Catatan: Gunakan `--no-dev` untuk lingkungan produksi.*

3.  **Instalasi Dependensi Frontend**
    ```bash
    npm install
    ```

4.  **Konfigurasi Lingkungan (.env)**
    -   Salin berkas konfigurasi contoh:
        ```bash
        cp .env.example .env
        ```
    -   Sesuaikan variabel kunci:
        -   `APP_NAME`: Nama aplikasi (misal: "SIM Buku Wisuda").
        -   `DB_CONNECTION`: `sqlite` atau `mysql`.
        -   `DB_DATABASE`: Nama basis data (atau path absolut untuk SQLite).

5.  **Pembuatan Basis Data & Migrasi**
    -   Jika menggunakan SQLite:
        ```bash
        touch database/database.sqlite
        ```
    -   Jalankan migrasi skema:
        ```bash
        php artisan migrate --force
        ```

6.  **Link Storage & Kunci Aplikasi**
    ```bash
    php artisan storage:link
    php artisan key:generate
    ```

7.  **Kompilasi Aset**
    ```bash
    npm run build
    ```

---

## 5. MANUAL PENGGUNAAN (USER MANUAL)

### 5.1 Modul Administrator

#### A. Otorisasi
Akses panel admin melalui URL `/login`. Masukkan kredensial yang valid. Kegagalan login berulang akan dibatasi oleh *rate limiter*.

#### B. Manajemen Buku Wisuda
Menu: **Kelola Buku Wisuda**
Fungsi ini digunakan untuk membuat periode wisuda baru.
-   **Tambah**: Klik tombol "Tambah Buku", isi Nama Periode dan Tanggal.
-   **Status**: Buku wisuda dapat diaktifkan atau dinonaktifkan untuk publik.

#### C. Manajemen Wisudawan
Menu: **Kelola Wisudawan**
-   **Import CSV**: Gunakan format CSV standar yang telah disediakan. Pastikan *header* kolom sesuai spesifikasi (`nim`, `nama`, `jurusan`, dll).
-   **Edit/Hapus**: Data indvidu dapat disunting jika terdapat kesalahan penulisan.

#### D. Manajemen Template & Arsip
Menu: **Kelola Arsip**
-   Pilih periode buku wisuda.
-   Pilih template desain yang diinginkan.
-   Klik **Generate PDF**. Proses ini mungkin memakan waktu bergantung pada jumlah data (waktu eksekusi PHP mungkin perlu disesuaikan untuk data besar).

### 5.2 Modul Publik
Dapat diakses tanpa login.
-   **Pencarian Alumni**: Masukkan Nama atau NIM pada kolom pencarian di `/cari-alumni`.
-   **Unduh Buku**: Pengunjung dapat mengunduh buku wisuda yang berstatus publik.

---

## 6. PEDOMAN PENGEMBANGAN DAN KONTRIBUSI

Bagian ini mengatur standar ketat bagi pengembang yang akan memodifikasi sistem.

### 6.1 Standar Kode (Strict Coding Standards)
1.  **Format**: Wajib mengikuti PSR-12. Gunakan *linter* (Laravel Pint) sebelum melakukan *commit*.
2.  **Penamaan**:
    -   Kelas: `PascalCase` (contoh: `WisudawanController`).
    -   Metode/Variabel: `camelCase` (contoh: `getWisudawanById`).
    -   Tabel Database: `snake_case` plural (contoh: `buku_wisudas`).
3.  **Komentar**: Setiap fungsi publik HARUS memiliki DocBlock yang menjelaskan parameter dan *return value*.

### 6.2 Alur Kerja Git (Git Workflow)
1.  Dilarang melakukan *commit* langsung ke `main` atau `master`.
2.  Gunakan percabangan fitur: `feature/nama-fitur-singkat`.
3.  Pesan commit harus deskriptif: `[TIPE] Deskripsi singkat` (contoh: `[FEAT] Menambahkan filter jurusan`).

### 6.3 Pelaporan Isu (Bug Reporting)
Jika ditemukan anomali sistem, laporkan dengan format:
-   **Langkah Reproduksi**: Urutan tindakan yang memicu error.
-   **Hasil yang Diharapkan**: Apa yang seharusnya terjadi.
-   **Hasil Aktual**: Apa yang terjadi (sertakan *screenshot* atau *stack trace*).

---

**DISCLAIMER:**
Dokumen ini bersifat rahasia dan hanya diperuntukkan bagi personel yang berwenang. Penggandaan atau penyebaran tanpa izin tertulis dilarang keras.
