<?php

namespace Database\Seeders;

use App\Models\BukuWisuda;
use App\Models\Wisudawan;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class WisudawanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $apiData = [];
        $totalStudents = 150;

        $faculties = [
            'Fakultas Syariah dan Hukum' => ['Hukum Keluarga', 'Hukum Ekonomi Syariah', 'Perbandingan Mazhab', 'Hukum Tata Negara'],
            'Fakultas Tarbiyah dan Keguruan' => ['Pendidikan Agama Islam', 'Pendidikan Bahasa Arab', 'Manajemen Pendidikan Islam', 'Pendidikan Matematika', 'Pendidikan Bahasa Inggris'],
            'Fakultas Ushuluddin dan Filsafat' => ['Aqidah dan Filsafat Islam', 'Sosiologi Agama', 'Ilmu Al-Quran dan Tafsir'],
            'Fakultas Dakwah dan Komunikasi' => ['Komunikasi dan Penyiaran Islam', 'Bimbingan dan Konseling Islam', 'Manajemen Dakwah'],
            'Fakultas Adab dan Humaniora' => ['Sejarah dan Kebudayaan Islam', 'Bahasa dan Sastra Arab', 'Ilmu Perpustakaan'],
            'Fakultas Ekonomi dan Bisnis Islam' => ['Ekonomi Syariah', 'Perbankan Syariah', 'Ilmu Ekonomi'],
            'Fakultas Sains dan Teknologi' => ['Teknologi Informasi', 'Arsitektur', 'Kimia', 'Biologi', 'Teknik Lingkungan'],
            'Fakultas Psikologi' => ['Psikologi'],
            'Fakultas Ilmu Sosial dan Ilmu Pemerintahan' => ['Ilmu Politik', 'Ilmu Administrasi Negara'],
        ];

        $thesisTopics = [
            'Hukum' => ['Analisis Hukum Waris', 'Tinjauan Fiqih Muamalah', 'Perlindungan Konsumen'],
            'Pendidikan' => ['Pengaruh Metode Pembelajaran', 'Efektivitas Media', 'Hubungan Motivasi'],
            'Dakwah' => ['Strategi Komunikasi', 'Pola Pembinaan Remaja', 'Peran Masjid'],
            'Ekonomi' => ['Analisis Kinerja Keuangan', 'Pengaruh Inflasi', 'Strategi Pemasaran'],
            'Teknologi' => ['Rancang Bangun Sistem', 'Penerapan Algoritma', 'Sistem Pendukung Keputusan'],
            'Psikologi' => ['Hubungan Kecerdasan Emosional', 'Stres Kerja', 'Dukungan Sosial'],
            'Umum' => ['Implementasi Kebijakan', 'Peran Pemerintah', 'Partisipasi Masyarakat']
        ];

        for ($i = 1; $i <= $totalStudents; $i++) {
            $facultyName = $faker->randomElement(array_keys($faculties));
            $prodiName = $faker->randomElement($faculties[$facultyName]);
            $gender = $faker->randomElement(['L', 'P']);
            
            $waveConfig = $faker->randomElement([
                ['gelombang' => '1', 'tahun' => '2025'],
                ['gelombang' => '2', 'tahun' => '2025'],
                ['gelombang' => '1', 'tahun' => '2026'],
            ]);

            $topicKey = 'Umum';
            if (str_contains($facultyName, 'Hukum') || str_contains($facultyName, 'Syariah')) $topicKey = 'Hukum';
            elseif (str_contains($facultyName, 'Tarbiyah')) $topicKey = 'Pendidikan';
            elseif (str_contains($facultyName, 'Dakwah')) $topicKey = 'Dakwah';
            elseif (str_contains($facultyName, 'Ekonomi')) $topicKey = 'Ekonomi';
            elseif (str_contains($facultyName, 'Teknologi')) $topicKey = 'Teknologi';
            elseif (str_contains($facultyName, 'Psikologi')) $topicKey = 'Psikologi';

            $thesis = $faker->randomElement($thesisTopics[$topicKey]) . ' ' . $faker->words(3, true) . ' di ' . $faker->city;

            $apiData[] = [
                'nim' => '200' . $faker->unique()->numberBetween(1000000, 9999999),
                'nama' => $faker->name($gender == 'L' ? 'male' : 'female'),
                'nomor_ijazah' => $faker->regexify('[0-9]{4}/UN\.08/DT\.III\.00\.00/[0-9]{4}'),
                'ttl' => $faker->city . ', ' . $faker->date('d F Y', '2003-01-01'),
                'jenis_kelamin' => $gender,
                'prodi' => $prodiName,
                'fakultas' => $facultyName,
                'ipk' => $faker->randomFloat(2, 3.00, 4.00),
                'yudisium' => $faker->randomElement(['Pujian', 'Sangat Memuaskan']),
                'judul_skripsi' => ucwords($thesis),
                'gelombang' => $waveConfig['gelombang'],
                'tahun' => $waveConfig['tahun'],
            ];
        }

        $this->command->info("Memulai sinkronisasi data dari API. Total data: " . count($apiData));
        
        $booksCache = [];
        $insertedCount = 0;

        foreach ($apiData as $student) {
            $gelombang = $student['gelombang'];
            $tahun = $student['tahun'];
            $bookKey = "{$gelombang}-{$tahun}";

            if (!isset($booksCache[$bookKey])) {
                $book = BukuWisuda::firstOrCreate(
                    ['gelombang' => $gelombang, 'tahun' => $tahun],
                    [
                        'nama_buku' => "Wisuda Gelombang $gelombang Tahun $tahun",
                        'tanggal_terbit' => now(),
                        'status' => 'Draft',
                        'slug' => \Illuminate\Support\Str::slug("Wisuda Gelombang $gelombang Tahun $tahun"),
                        'file_pdf' => null
                    ]
                );
                $booksCache[$bookKey] = $book->id;
            }

            Wisudawan::create([
                'id_buku' => $booksCache[$bookKey],
                'nim' => $student['nim'],
                'nama' => $student['nama'],
                'nomor' => $student['nomor_ijazah'],
                'ttl' => $student['ttl'],
                'jenis_kelamin' => $student['jenis_kelamin'],
                'prodi' => $student['prodi'],
                'fakultas' => $student['fakultas'],
                'ipk' => $student['ipk'],
                'ka_yudisium' => $student['yudisium'],
                'judul_thesis' => $student['judul_skripsi'],
                'foto' => '',
            ]);
            $insertedCount++;
        }

        $this->command->info("Sinkronisasi API Selesai: {$insertedCount} mahasiswa berhasil ditambahkan ke dalam buku wisuda.");
    }
}
