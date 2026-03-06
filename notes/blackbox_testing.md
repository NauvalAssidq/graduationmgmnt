# Laporan Pengujian Blackbox (Blackbox Testing) - SIM Buku Wisuda

Laporan ini memuat hasil pengujian terperinci berdasarkan Fungsionalitas, Menu (Controller), dan operasi CRUD (Create, Read, Update, Delete) serta interaksi komponen seperti tombol dan pencarian skala penuh. Kolom Keparahan (Severity) telah ditambahkan untuk memudahkan prioritas perbaikan pada aspek Front-end maupun Back-end yang belum optimal.

## 1. Fungsionalitas Publik (Front-end & Interaksi Beranda)

| ID | Modul / Halaman | Skenario Uji | Hasil yang Diharapkan | Hasil Aktual | Status | Keparahan |
|---|---|---|---|---|---|---|
| PUB-01 | Halaman Utama | Mengakses halaman `/` dan merender UI dasar | Seluruh aset front-end (gambar, font, layout) termuat mulus. | Halaman utama terbuka dengan baik tanpa resource block. | Berhasil | - |
| PUB-02 | Fitur Carian (Search) | Membiarkan form pencarian kosong lalu menekan tombol "Cari" (`/cari-alumni`) | Terdapat HTML5 form validation atau tombol tidak bereaksi, menghindari beban query kosong. | Navigasi tetap merespon, form divalidasi dengan baik dan sistem tidak melempar error. | Berhasil | - |
| PUB-03 | Fitur Carian (Search) | Melakukan kueri pencarian acak ("xyz123abc") untuk memicu "Not Found" | Menampilkan indikator elegan "Data tidak ditemukan" di UI hasil pencarian. | Halaman pencarian ter-render dengan baik dan menampilkan list kosong tanpa men-trigger `Error 500`. | Berhasil | - |
| PUB-04 | Validasi Login | Admin mengosongkan seluruh formulir Login lalu "Masuk" | Teks peringatan jelas berwarna merah (misalnya, "Email wajib diisi") langsung di bawah input box. | Form secara umum ter-reload, keamanan bagus namun umpan balik visual validasinya sangat lemah dan kurang terlihat ("Silent Reload"). | Peringatan | Rendah |

## 2. Menu: Dashboard Admin (Analitik)

| ID | Modul / Bagian | Skenario Uji | Hasil yang Diharapkan | Hasil Aktual | Status | Keparahan |
|---|---|---|---|---|---|---|
| DASH-01 | Render Diagram | Menyorot elemen visualisasi "Distribusi data" dengan kursor mouse. | Layout stabil dan responsif muncul popup data terkait (Hover Event). | Grafik berhasil merender dengan mulus tanpa pergeseran div di sekitarnya. | Berhasil | - |
| DASH-02 | Fungsionalitas Navbar | Mengetik dan mencari fitur via Search Bar atas (Navbar Admin). | Memiliki State Focus, box membesar/menyala dan mengetik berjalan lanacar. | Komponen responsif, focus tertrigger dengan sempurna. | Berhasil | - |

## 3. Menu Controller: Kelola Wisudawan (CRUD Integritas)

| ID | Skenario Uji | Tindakan Sistem yang Diharapkan | Hasil Aktual | Status | Keparahan |
|---|---|---|---|---|---|
| WSD-01 (Create) | Menekan "Simpan Data" sementara seluruh form kosong. | Blokir submisi secara harfiah dan menandai elemen form berwarna merah. | Permintaan diblokir, tidak terjadi error di server, lalu direload tapi indikator UX warna peringatan tidak terlihat. | Peringatan | Sedang |
| WSD-02 (Create) | Menyimpan data Wisudawan logis namun membiarkan file Foto Kosong. | Diberi pemberitahuan *"Foto Wajib Diisi"* secara spesifik oleh sistem, penumpukan data ditunda. | Web melakukan operasikan simpan seolah berhasil dan mengembalikan pengguna ke tabel, namun Record TIDAK tersimpan ke dalam database (Silent Fail/Kehilangan Data Sesi). | Gagal | Tinggi |
| WSD-03 (Update) | Menyorot baris data lalu menekan tombol "Edit" dan mengganti angka NIM perwakilan. | Identitas data terubah sesuai modifikasi secara real-time. | Data diperbarui sempurna, kembali dirender di layar Tabel. | Berhasil | - |
| WSD-04 (Read) | Memanfaatkan Filter "Search" milik list wisudawan dengan sebagian identitas. | Tabel segera di filter dan mengeksekusi sisa tabel yang dituju. | Tombol dan input bekerja menyortir data dengan prima dan presisi. | Berhasil | - |
| WSD-05 (Delete) | Memilih "Hapus Data" dan sistem mengonfirmasi sebelum melempar command DELETE. | Box Pop-up muncul untuk memastikan aksi tersebut aman dari Miss-Click. | Fungsi Modal menghalangi aksi tak sengaja, berjalan sangat mulus. Pembatalan menahan proses, Persetujuan membersihkan database. | Berhasil | - |

## 4. Menu Controller: Kelola Buku & Master Data (CRUD Transaksional)

| ID | Skenario Uji | Tindakan Sistem yang Diharapkan | Hasil Aktual | Status | Keparahan |
|---|---|---|---|---|---|
| BKU-01 (Create) | Memberikan nama buku unik dan valid | Menyimpan master record ke tabel relasional | Berhasil terangkum dan teregistrasi sistem untuk siap dialokasikan. | Berhasil | - |
| BKU-02 (Update) | Menutup dan merevisi kembali nama pada Buku Wisuda | Label nama termodifikasi dan tabel ikut ter-refresh | Berhasil diubah "Wisuda Gelombang 3 Tahun 2025" menjadi nama baru tanpa error. | Berhasil | - |
| BKU-03 (Delete) | Memaksakan hapus Data Buku yang ternyata digunakan oleh banyak Siswa | Menolak keras perintah, meminta admin menetralkan relasinya (Constraint FK Check). | Meloloskan request penghapusan ke Controller, menyebabkan `SQLSTATE Error (Foreign Key Constraint Fails)` - merusak pengalaman interaktif. | Gagal | Tinggi |

## 5. Lainnya: Arsip dan Templat (Fitur File Management)

| ID | Skenario Uji | Tindakan Sistem yang Diharapkan | Hasil Aktual | Status | Keparahan |
|---|---|---|---|---|---|
| MISC-01 | Pengaturan Form | Menyusupkan dan mengedit file layout dengan rasio acak/terlalu besar. | Tertahan dan menolak ukuran file, error *Out of Bounds* rapih tersajikan. | Gambar di-bypass, mengakibatkan pecahnya styling bingkai template render. | Peringatan | Sedang |
| MISC-02 | File Interaktif | Mengakses detail serta mengklik arsip dokumen. | Menyajikan *Download Path* yang utuh tidak rusak. | Tombol aktif dan dapat menangkap trigger download dengan spesifikasi MIME yang benar. | Berhasil | - |

---

### Kesimpulan Prioritas Perbaikan Sistem (Severity Alert)

1. **[Tinggi] Fix Bug Entry Wisudawan:** Mekanisme "Silent Fail" saat penambahan form (Create Kelola Wisudawan) wajib diselesaikan. Pengguna mengalami ilusi keberhasilan entri padahal proses penyimpanan gagal.
2. **[Tinggi] Handlers Relasional (Delete Buku):** Implementerasikan Catch Exception untuk menghalau `Error 500` saat penghapusan buku yang mengikat ribuan profil wisudawan. Gantilah dengan alert visual yang ramah.
3. **[Sedang] Perombakan CSS/UI Validation:** Lengkapi atribut antarmuka dengan Border merah dan teks bantu (`Text-danger`) yang sangat jelas terlihat apabila pengguna lalai mengisi bagian spesifik formulir maupun Login.
