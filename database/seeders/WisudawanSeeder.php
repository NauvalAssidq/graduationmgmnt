<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use App\Models\BukuWisuda;
use App\Models\Wisudawan;

class WisudawanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // Ensure a Book exists
        $buku = BukuWisuda::first();
        if (!$buku) {
            $buku = BukuWisuda::create([
                'nama_buku' => 'Wisuda Gelombang I Tahun 2026',
                'tanggal_terbit' => '2026-03-15',
                'gelombang' => 'I',
                'tahun' => '2026',
                'status' => 'Aktif',
                'file_pdf' => null
            ]);
        }

        $faculties = [
            'Fakultas Syariah dan Hukum' => [
                'Hukum Keluarga', 'Hukum Ekonomi Syariah', 'Perbandingan Mazhab', 'Hukum Tata Negara', 'Hukum Pidana Islam'
            ],
            'Fakultas Tarbiyah dan Keguruan' => [
                'Pendidikan Agama Islam', 'Pendidikan Bahasa Arab', 'Manajemen Pendidikan Islam', 
                'Pendidikan Matematika', 'Pendidikan Bahasa Inggris', 'Pendidikan Kimia', 'Pendidikan Fisika'
            ],
            'Fakultas Ushuluddin dan Filsafat' => [
                'Aqidah dan Filsafat Islam', 'Sosiologi Agama', 'Ilmu Al-Quran dan Tafsir', 'Studi Agama-Agama'
            ],
            'Fakultas Dakwah dan Komunikasi' => [
                'Komunikasi dan Penyiaran Islam', 'Bimbingan dan Konseling Islam', 'Manajemen Dakwah', 'Pengembangan Masyarakat Islam'
            ],
            'Fakultas Adab dan Humaniora' => [
                'Sejarah dan Kebudayaan Islam', 'Bahasa dan Sastra Arab', 'Ilmu Perpustakaan'
            ],
            'Fakultas Ekonomi dan Bisnis Islam' => [
                'Ekonomi Syariah', 'Perbankan Syariah', 'Ilmu Ekonomi', 'Akuntansi Syariah'
            ],
            'Fakultas Sains dan Teknologi' => [
                'Teknologi Informasi', 'Arsitektur', 'Kimia', 'Biologi', 'Teknik Lingkungan', 'Sistem Informasi'
            ],
            'Fakultas Psikologi' => ['Psikologi'],
            'Fakultas Ilmu Sosial dan Ilmu Pemerintahan' => ['Ilmu Politik', 'Ilmu Administrasi Negara'],
        ];

        $thesisTopics = [
            'Hukum' => ['Analisis Hukum Waris', 'Tinjauan Fiqih Muamalah terhadap', 'Perlindungan Konsumen dalam Perspektif', 'Sanksi Pidana Islam terhadap'],
            'Pendidikan' => ['Pengaruh Metode Pembelajaran', 'Efektivitas Media', 'Hubungan Motivasi dan Prestasi', 'Strategi Guru PAI dalam'],
            'Dakwah' => ['Strategi Komunikasi Dakwah', 'Pola Pembinaan Remaja', 'Peran Masjid dalam', 'Dinamika Sosial Keagamaan'],
            'Ekonomi' => ['Analisis Kinerja Keuangan', 'Pengaruh Inflasi terhadap', 'Strategi Pemasaran Syariah', 'Peran UMKM dalam'],
            'Teknologi' => ['Rancang Bangun Sistem Informasi', 'Penerapan Algoritma', 'Sistem Pendukung Keputusan', 'Analisis Keamanan Jaringan'],
            'Psikologi' => ['Hubungan Kecerdasan Emosional dengan', 'Stres Kerja pada', 'Dukungan Sosial dan', 'Resiliensi pada'],
            'Umum' => ['Implementasi Kebijakan', 'Peran Pemerintah Daerah', 'Partisipasi Masyarakat dalam', 'Evaluasi Program']
        ];

        // Islamic Name Components to mix with Faker
        $islamicPrefixesM = ['Muhammad', 'Ahmad', 'Abdul', 'Zainal', 'Fajri', 'Ilham', 'Rahmat'];
        $islamicPrefixesF = ['Siti', 'Nur', 'Aisyah', 'Fatimah', 'Zahra', 'Putri', 'Wardah'];
        
        $graduates = [];

        for ($i = 1; $i <= 100; $i++) {
            // Pick Faculty and Prodi
            $facultyName = $faker->randomElement(array_keys($faculties));
            $prodiList = $faculties[$facultyName];
            $prodiName = $faker->randomElement($prodiList);

            // Gender
            $gender = $faker->randomElement(['L', 'P']);
            
            // Name Generation
            $firstName = $gender == 'L' 
                ? $faker->randomElement($islamicPrefixesM) 
                : $faker->randomElement($islamicPrefixesF);
            $fullName = $firstName . ' ' . $faker->lastName . ' ' . ($faker->boolean(40) ? $faker->lastName : '');

            // Thesis Generation logic based on Faculty keywords
            $topicKey = 'Umum';
            if (str_contains($facultyName, 'Hukum') || str_contains($facultyName, 'Syariah')) $topicKey = 'Hukum';
            elseif (str_contains($facultyName, 'Tarbiyah')) $topicKey = 'Pendidikan';
            elseif (str_contains($facultyName, 'Dakwah')) $topicKey = 'Dakwah';
            elseif (str_contains($facultyName, 'Ekonomi')) $topicKey = 'Ekonomi';
            elseif (str_contains($facultyName, 'Teknologi')) $topicKey = 'Teknologi';
            elseif (str_contains($facultyName, 'Psikologi')) $topicKey = 'Psikologi';

            $prefix = $faker->randomElement($thesisTopics[$topicKey]);
            $suffix = $faker->words(4, true);
            $location = $faker->city;
            $thesisTitle = "$prefix $suffix di $location";

            // IPK & Yudisium
            $ipk = $faker->randomFloat(2, 3.00, 3.99);
            $yudisium = 'Sangat Baik';
            if ($ipk >= 3.75) $yudisium = 'Cumlaude'; // or Pujian often used
            elseif ($ipk >= 3.50) $yudisium = 'Pujian'; // or Sangat Baik
            else $yudisium = 'Baik';

            $graduates[] = [
                'id_buku' => $buku->id,
                'nama' => strtoupper($fullName),
                'nim' => '20' . $faker->numerify('#######'), // 20xxxxxxx (approx Year 2020 entry)
                'nomor' => str_pad($i, 3, '0', STR_PAD_LEFT),
                'ttl' => strtoupper($faker->city . ', ' . $faker->date('d F Y', '2003-12-31')),
                'jenis_kelamin' => $gender,
                'prodi' => trim(str_replace('Fakultas', '', $facultyName)) . " - " . $prodiName, // Just formatting preference
                'prodi' => $prodiName,
                'fakultas' => $facultyName,
                'ipk' => $ipk,
                'ka_yudisium' => $yudisium,
                'judul_thesis' => ucwords($thesisTitle),
                'foto' => '', // Empty string since column is not nullable
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        // Sort by Faculty and Prodi for realistic ordering usually
        // But mass insert is fine.
        
        foreach (array_chunk($graduates, 50) as $chunk) {
            Wisudawan::insert($chunk);
        }
    }
}
