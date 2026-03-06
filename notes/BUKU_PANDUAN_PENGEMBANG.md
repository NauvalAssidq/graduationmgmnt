# BUKU PANDUAN PENGEMBANG DAN ARSITEKTUR SISTEM (DEVELOPER HANDBOOK)

**Nama Proyek:** SIM Buku Wisuda (Graduation Management System)  
**Tingkat Dokumen:** RAHASIA (INTERNAL DEVELOPER ONLY)  
**Versi Dokumen:** 4.0.0 (Extended Technical Reference)  
**Tanggal Pembaruan:** 7 Februari 2026  
**Penulis:** Tim Pengembang Inti UIN Ar-Raniry  

---

## DAFTAR ISI

1.  [BAB I: FILOSOFI PENGEMBANGAN DAN EKOSISTEM](#bab-1)
2.  [BAB II: KONFIGURASI LINGKUNGAN (ENVIRONMENT)](#bab-2)
3.  [BAB III: ARSITEKTUR DATABASE DAN MODEL DATA](#bab-3)
4.  [BAB IV: INTI LOGIKA BACKEND (CONTROLLERS)](#bab-4)
5.  [BAB V: FRONTEND ENGINE & VISUALISASI](#bab-5)
6.  [BAB VI: SISTEM TEMPLATING DINAMIS](#bab-6)
7.  [BAB VII: ANALITIK DAN DASHBOARD](#bab-7)
8.  [BAB VIII: STANDAR PROSEDUR OPERASIONAL (SOP)](#bab-8)
9.  [BAB IX: KAMUS TROUBLESHOOTING](#bab-9)
10. [BAB X: KATALOG SNIPPET KODE (COOKBOOK)](#bab-10)
11. [BAB XI: RIWAYAT PERUBAHAN & ROADMAP](#bab-11)

---

<a name="bab-1"></a>
## BAB I: FILOSOFI PENGEMBANGAN DAN EKOSISTEM

### 1.1 Visi Teknis
Sistem ini dibangun dengan visi "Kemandirian dan Kualitas Visual". Tidak seperti sistem administrasi kampus pada umumnya yang kaku, SIM Buku Wisuda menggabungkan ketatnya manajemen data dengan estetika visual modern.

### 1.2 Paradigma "Stability-First"
Kami meyakini bahwa sistem produksi tidak boleh "rapuh". Oleh karena itu, prinsip berikut dipegang teguh:
1.  **Fail-Safe Operations**: Satu data yang korup (misal: encoding CSV salah) tidak boleh mematikan seluruh proses impor.
2.  **Resource Bounded**: PDF Generation dibatasi memori dan waktunya secara eksplisit di level kode, tidak bergantung konfigurasi default PHP.
3.  **Strict Typing**: Kami menggunakan fitur PHP 8.2 modern. `string $nama`, bukan `$nama`. `int $id`, bukan `$id`.
4.  **No Magic Numbers**: Jangan gunakan angka mentah (misal: `status = 1`). Gunakan Enum atau Konstanta Class.

### 1.3 Stack Teknologi (The TALL Stack Variant)
*   **Operating System**: Linux (Ubuntu/Debian recommended) / Dockerized.
*   **Backend Framework**: Laravel 12.x.
*   **Frontend Styling**: TailwindCSS v4.0 (Utility-first).
*   **Frontend Logic**: Alpine.js v3 (Ringan, tanpa Virtual DOM berat).
*   **Build Tool**: Vite (Untuk HMR saat dev dan bundling saat prod).
*   **PDF Engine**: `barryvdh/laravel-dompdf` (Wrapper dompdf).
*   **Database**: SQLite (Default) / MySQL 8.0+.

---

<a name="bab-2"></a>
## BAB II: KONFIGURASI LINGKUNGAN (ENVIRONMENT)

File `.env` mengatur perilaku sistem. Jangan pernah melakukan commit file ini ke repositori.

### 2.1 Variabel Aplikasi Utama
```ini
APP_NAME="SIM Buku Wisuda"
APP_ENV=local           # Ubah ke 'production' saat live. 
                        # Jika 'local', debug bar akan muncul dan error ditampilkan.
APP_KEY=base64:...      # Wajib digenerate: php artisan key:generate
APP_DEBUG=true          # WAJIB FALSE di production untuk mencegah kebocoran stack trace.
APP_URL=http://localhost # Berpengaruh pada link asset dan routing email.
```

### 2.2 Konfigurasi Database
Secara default, sistem menggunakan SQLite untuk kemudahan mobilitas.
```ini
# SQLite (Tanpa setup server database)
DB_CONNECTION=sqlite
# DB_DATABASE tidak perlu diisi jika menggunakan path standar database/database.sqlite
```
Jika beralih ke MySQL (untuk high concurrency > 1000 user/detik):
```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_wisuda
DB_USERNAME=root
DB_PASSWORD=secret
```

### 2.3 Integrasi Data Eksternal
```ini
# Endpoint API untuk sinkronisasi data wisudawan otomatis (Opsional)
# Jika kosong, sistem fallback ke metode Import CSV manual.
WISUDAWAN_DATA_URL=https://api.kampus.ac.id/v1/graduates
```

### 2.4 Symlink Storage
Karena Laravel menyimpan file upload di `storage/app/public`, web server tidak bisa mengaksesnya langsung. Anda **WAJIB** membuat symbolic link ke folder `public`:
```bash
php artisan storage:link
```
*Tanda sukses: Muncul folder `public/storage`.*

---

<a name="bab-3"></a>
## BAB III: ARSITEKTUR DATABASE DAN MODEL DATA

Kami menggunakan desain skema yang ternormalisasi namun pragmatis.

### 3.1 Tabel: `wisudawan` (Inti Data)
Menyimpan profil lulusan.
*   `id` (BigInt): Primary Key.
*   `id_buku` (FK): Relasi ke tabel periodisasi wisuda `(buku_wisuda)`. On Delete: Cascade/Restrict (Tergantung kebijakan).
*   `nim` (String, Unique): Mencegah data ganda. Indexing pada kolom ini wajib untuk performa pencarian.
*   `ka_yudisium` (String): Menyimpan predikat (Cumlaude, Sangat Memuaskan). Digunakan untuk filter statistik di Dashboard.

### 3.2 Tabel: `buku_wisuda` (Periodisasi)
Mengelompokkan wisudawan.
*   `status` (Enum):
    *   `Draft`: Sedang disusun, tidak muncul di publik.
    *   `Published`: Muncul di Landing Page.
    *   `Archived`: Read-only.
*   `slug`: Digunakan untuk *Pretty URL* (`/buku/gelombang-1-2025`).
*   `file_pdf`: Path file PDF yang sudah digenerate.

### 3.3 Tabel: `template_buku_wisuda` (Desain Dinamis)
Menyimpan layout buku. Menggunakan `nama` sebagai Primary Key (String) untuk memudahkan pembacaan di kode.
*   `cover_html` (Text): Menyimpan struktur HTML cover.
*   `custom_css` (Text): CSS spesifik untuk layout tersebut.
*   `layout` (Enum): `A4`, `F4`, `Booklet`.

---

<a name="bab-4"></a>
## BAB IV: INTI LOGIKA BACKEND (CONTROLLERS)

### 4.1 `WisudawanController`: Algoritma Import Fail-Safe
Fitur impor CSV dirancang untuk menangani ribuan data sekaligus tanpa membebani memori server.

**Alur Logika `import()`:**
1.  **Validasi Awal**: Memastikan file adalah CSV/TXT valid.
2.  **Streaming Read**: Menggunakan `fopen` dan `fgetcsv` dalam loop `while`. Ini jauh lebih efisien memori daripada memuat seluruh file ke array.
3.  **Atomic Try-Catch**: Setiap baris diproses dalam blok `try`.
    ```php
    while (($row = fgetcsv($handle)) !== false) {
        try {
            // Mapping array CSV ke kolom database
            Wisudawan::create([
                'nim' => $row[0],
                // ...
            ]);
            $count++;
        } catch (\Exception $e) {
            // Baris error di-skip, loop lanjut ke baris berikutnya.
            // Log error jika perlu: Log::error($e);
            continue; 
        }
    }
    ```
    *Keuntungan*: Jika baris ke-999 rusak, 998 data sebelumnya tetap tersimpan.

### 4.2 `ArsipController`: Engine PDF Generasi
Menangani render HTML ke PDF menggunakan engine Webkit-based (dompdf).

**Optimasi Kritis:**
1.  `set_time_limit(300)`: Memberi waktu 5 menit. Default PHP biasanya 30 detik, yang pasti akan timeout untuk buku >50 halaman.
2.  `ini_set('memory_limit', '512M')`: PDF rendering sangat boros RAM.
3.  **Toggle Logic**: Tombol generate berfungsi ganda (Buat/Hapus) untuk menghemat storage.

### 4.3 `PublicController`: Strategi Pencarian Terpisah
Agar Landing Page tetap ringan, pencarian dipisah menjadi dua:
1.  **Cari Buku**: Filter ringan berdasarkan Nama Buku/Tahun.
2.  **Cari Alumni**: Filter berat (JOIN table) yang diarahkan ke halaman hasil pencarian terpisah (`/cari-alumni`).

---

<a name="bab-5"></a>
## BAB V: FRONTEND ENGINE & VISUALISASI

### 5.1 Komponen 3D Book (`x-book-3d`)
Terletak di `resources/views/components/book-3d.blade.php`.
Menggunakan teknik CSS `transform-style: preserve-3d`.

**Struktur Layer:**
*   **Cover Depan**: Bisa dibuka (`rotateY(-145deg)`). Memiliki sisi luar dan dalam.
*   **Spine (Punggung)**: Menyambungkan depan dan belakang.
*   **Pages Block**: Div tebal dengan gradient untuk mensimulasikan tumpukan kertas.
*   **Trigger**: Alpine.js `x-data="{ open: false }"` menangani state klik.

### 5.2 Iframe Isolation (Preview Template)
Pada daftar buku, preview template ditampilkan dalam `<iframe>` dengan atribut `srcdoc`.
**Mengapa Iframe?**
CSS template buku (misal: header fonts, margins) seringkali bertabrakan dengan CSS Tailwind aplikasi utama. Iframe menciptakan "Sandbox" sempurna sehingga tampilan preview 100% akurat dengan hasil cetak PDF.

---

<a name="bab-6"></a>
## BAB VI: SISTEM TEMPLATING DINAMIS

### 6.1 Editor Template (`x-code-editor`)
Di panel Admin (`admin/template/create`), kami menggunakan komponen editor kode khusus.
Ini memungkinkan admin memasukkan HTML dan CSS mentah yang akan disimpan ke database.

### 6.2 Render Logic
Saat PDF dibuat, sistem akan:
1.  Mengambil `cover_html` dari tabel template.
2.  Melakukan *binding* data (mengganti variabel dummy dengan data `BukuWisuda` asli).
3.  Menyuntikkan `custom_css` ke dalam tag `<style>` di head dokumen PDF.

---

<a name="bab-7"></a>
## BAB VII: ANALITIK DAN DASHBOARD

### 7.1 Pengecualian Aturan "No Raw Queries"
Di `DashboardController`, Anda akan menemukan penggunaan `DB::raw` untuk agregasi data.
```php
Wisudawan::select('fakultas', DB::raw('count(*) as total'))
    ->groupBy('fakultas')
    ->pluck('total', 'fakultas');
```
**Alasan**: Menggunakan Eloquent collection (`Wisudawan::all()->groupBy()`) akan memuat ribuan objek ke RAM PHP hanya untuk dihitung jumlahnya. SQL `GROUP BY` jauh lebih efisien dan dilakukan di level database.

### 7.2 Statistik Utama
*   **Total Cumlaude**: Filter string `LIKE %Cumlaude%` pada kolom `ka_yudisium`.
*   **Rata-rata IPK**: Menggunakan `avg()` bawaan database.

---

<a name="bab-8"></a>
## BAB VIII: STANDAR PROSEDUR OPERASIONAL (SOP)

### 8.1 SOP Deploy ke Production
1.  **Pull Code**: `git pull origin main`
2.  **Install Dept**: `composer install --optimize-autoloader --no-dev`
3.  **Migrate**: `php artisan migrate --force` (Hati-hati, backup DB dulu!)
4.  **Build Assets**: `npm run build`
5.  **Cache Config**:
    ```bash
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
    ```
6.  **Restart Queue** (Jika pakai queue worker): `php artisan queue:restart`

### 8.2 SOP Update Template Buku
1.  Jangan edit template yang sedang AKTIF digunakan oleh buku berstatus `Published`, kecuali mendesak.
2.  Lebih baik buat Template Baru (v2), lalu ubah relasi buku ke template baru tersebut.

---

<a name="bab-9"></a>
## BAB IX: KAMUS TROUBLESHOOTING

| Gejala | Kemungkinan Penyebab | Solusi |
| :--- | :--- | :--- |
| **Gambar PDF Silang (X)** | Path gambar relative/URL tidak bisa diakses server. | Gunakan `storage_path('app/public/...')` (path absolut file system) di view PDF. |
| **Error 419 Page Expired** | Token CSRF kedaluwarsa atau hilang. | Pastikan `<form>` memiliki direktif `@csrf`. |
| **Import CSV Gagal/Kosong** | Delimiter CSV bukan koma (`,`), misal titik koma (`;`). | Pastikan format CSV standar (Comma Separated). Cek file di Excel/Text Editor. |
| **Putih Blank (WSOD)** | Error terjadi tapi `APP_DEBUG=false`. | Cek log di `storage/logs/laravel.log`. |
| **Styling Berantakan** | Aset `build` belum digenerate. | Jalankan `npm run build`. Hapus cache browser. |
| **Permission Denied** | User web server (www-data) tidak bisa tulis file. | `chown -R www-data:www-data storage bootstrap/cache` |

---

<a name="bab-10"></a>
## BAB X: KATALOG SNIPPET KODE (COOKBOOK)

Berikut adalah *Gold Standard* snippet kode untuk digunakan dalam pengembangan fitur baru.

### 10.1 Model dengan Clean Scope
```php
// App/Models/Wisudawan.php

/**
 * Scope local untuk filter cumlaude.
 * Penggunaan: Wisudawan::cumlaude()->get();
 */
public function scopeCumlaude($query)
{
    return $query->where('ka_yudisium', 'like', '%Cumlaude%');
}

/**
 * Accessor untuk foto URL publik.
 * Penggunaan: $wisudawan->foto_url
 */
public function getFotoUrlAttribute()
{
    return $this->foto 
        ? asset('storage/' . $this->foto) 
        : asset('images/default-avatar.png');
}
```

### 10.2 Controller Method Standar
```php
// App/Http/Controllers/Admin/NewFeatureController.php

public function store(StoreRequest $request): RedirectResponse
{
    // 1. Otorisasi (jika belum di Request Class)
    // $this->authorize('create', Model::class);

    // 2. Transaksi Database (Atomic)
    DB::transaction(function () use ($request) {
        $model = Model::create($request->validated());
        
        // Log aktivitas penting
        Log::info('Fitur baru dibuat oleh admin: ' . auth()->id());
    });

    // 3. Return dengan Flash Message
    return redirect()
        ->route('admin.feature.index')
        ->with('success', 'Data berhasil disimpan.');
}
```

---

<a name="bab-11"></a>
## BAB XI: RIWAYAT PERUBAHAN & ROADMAP

### 11.1 Changelog Dokumen
*   **v1.0.0**: Rilis awal panduan dasar CRUD.
*   **v2.0.0**: Penambahan bab Arsitektur Frontend dan PDF Engine.
*   **v3.0.0**: Integrasi SOP Deployment dan Troubleshooting.
*   **v4.0.0 (SEKARANG)**: Penambahan Katalog Snippet Kode dan detail `Code Editor` template.

### 11.2 Rencana Fitur Mendatang (Roadmap)
*   [ ] **Queue Worker**: Memindahkan proses Generate PDF ke background job (Redis/Database) agar user tidak perlu menunggu loading browser.
*   [ ] **S3 Storage**: Integrasi AWS S3 untuk penyimpanan aset PDF jangka panjang.
*   [ ] **2FA**: Two-Factor Authentication untuk akun Admin.

---
**AKHIR DOKUMEN**
*Dokumen ini bersifat rahasia. Dilarang menggandakan tanpa izin Supervisor IT UIN Ar-Raniry.*
