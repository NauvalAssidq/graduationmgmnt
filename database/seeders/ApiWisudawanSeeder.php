<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ApiWisudawanSeeder extends Seeder
{
    public function run(): void
    {
        $connection = 'sqlite_api';
        DB::connection($connection)->table('wisudawan')->delete();


        $fakultas = [
            'Fakultas Syariah dan Hukum' => [
                'Hukum Keluarga Islam', 'Perbandingan Mazhab', 'Hukum Ekonomi Syariah',
                'Hukum Tata Negara',
            ],
            'Fakultas Tarbiyah dan Keguruan' => [
                'Pendidikan Agama Islam', 'Pendidikan Bahasa Arab',
                'Manajemen Pendidikan Islam', 'Pendidikan Matematika',
            ],
            'Fakultas Dakwah dan Komunikasi' => [
                'Komunikasi dan Penyiaran Islam', 'Bimbingan dan Konseling Islam',
                'Manajemen Dakwah',
            ],
            'Fakultas Ushuluddin dan Filsafat' => [
                'Ilmu Al-Quran dan Tafsir', 'Aqidah dan Filsafat Islam',
                'Sosiologi Agama',
            ],
            'Fakultas Sains dan Teknologi' => [
                'Teknik Informatika', 'Sistem Informasi', 'Matematika',
                'Biologi', 'Kimia',
            ],
            'Fakultas Ilmu Sosial dan Ilmu Pemerintahan' => [
                'Ilmu Administrasi Negara', 'Ilmu Politik', 'Sosiologi',
            ],
            'Fakultas Ekonomi dan Bisnis Islam' => [
                'Perbankan Syariah', 'Ekonomi Syariah', 'Akuntansi Syariah',
            ],
            'Pascasarjana' => [
                'Pendidikan Islam', 'Hukum Islam', 'Ekonomi Islam',
            ],
        ];

        $maleNames = [
            'Ahmad Fauzi', 'Muhammad Rizki', 'Andika Pratama', 'Farhan Maulana',
            'Rizal Hakim', 'Ilham Syahputra', 'Daffa Akbar', 'Naufal Hidayat',
            'Reza Firmansyah', 'Yusuf Abdillah', 'Haris Munandar', 'Bagas Wicaksono',
            'Fadli Rahman', 'Zulfikar Azhari', 'Alif Prabowo', 'Hafidz Maulana',
            'Kevin Faturahman', 'Ridwan Santoso', 'Wahyu Nugroho', 'Gibran Rakabuming',
            'Arif Budiman', 'Iqbal Ramadhan', 'Dzikri Hamdani', 'Fadhilah Akbar',
            'Rayhan Saputra', 'Syahril Anshori', 'Ikhwan Mubarak', 'Tsabit Fuadi',
            'Abdul Ghani', 'Faris Alamsyah', 'Hendra Kusuma', 'Prasetyo Budi',
            'Nabil Muttaqin', 'Faisal Amin', 'Aufa Hanif', 'Zaki Mustafa',
            'Aziz Harahap', 'Rafi Athallah', 'Dito Ariyanto', 'Fajar Hidayatullah',
            'Aldo Pratama', 'Barid Ihsanul', 'Ihsan Kamil', 'Febrian Saputra',
            'Agung Wibowo', 'Taufiqurrahman', 'Lukman Hakim', 'Syamsul Bahri',
            'Putra Ramadhan', 'Azhar Maulana',
        ];

        $femaleNames = [
            'Siti Rahma', 'Nurul Hidayah', 'Aisyah Putri', 'Fatimah Az-Zahra',
            'Rizky Amalia', 'Dina Fitriani', 'Wulandari Putri', 'Nabila Azzahra',
            'Zahra Khairunnisa', 'Intan Permata', 'Aulia Ningsih', 'Rania Salsabila',
            'Maysarah Hanum', 'Hasna Fadhilah', 'Laila Nuraini', 'Khairunnisa',
            'Putri Rahmawati', 'Sarah Adzkia', 'Humaira Salwa', 'Naila Ramadhani',
            'Tiara Anggraini', 'Dewi Rahmayanti', 'Ulfa Mardiana', 'Suci Rahayu',
            'Lailatul Fitri', 'Mona Syafitri', 'Yulia Handayani', 'Azizah Sari',
            'Fathimah Nisa', 'Ririn Setiawati', 'Anna Fitriyani', 'Lia Oktaviani',
            'Mira Agustina', 'Tasya Kamila', 'Salma Aulia', 'Nada Salsabila',
            'Dwi Ramadhani', 'Cindy Permata', 'Rahmi Fadillah', 'Kurnia Sari',
            'Safira Maulida', 'Elisa Nurjannah', 'Mariam Ulfa', 'Indah Pertiwi',
            'Annisa Rahmah', 'Rona Aulia', 'Syifa Alfiani', 'Yanti Nuraini',
            'Bunga Mutiara', 'Lina Rahmawati',
        ];

        $kaYudisiumOptions = [
            'Dengan Pujian',
            'Sangat Memuaskan',
            'Memuaskan',
        ];

        $nominorCounter = 1000;
        $wisudawanRows  = [];
        $usedNims       = [];
        $bookIds        = [1, 2, 3, 4];

        $bookDistribution = [1 => 40, 2 => 50, 3 => 60, 4 => 50];

        foreach ($bookDistribution as $bookId => $count) {
            $bookYear = ($bookId <= 2) ? '2023' : '2024';

            for ($i = 1; $i <= $count; $i++) {
                $isMale = rand(0, 1) === 1;
                $gender = $isMale ? 'L' : 'P';
                $namePool = $isMale ? $maleNames : $femaleNames;
                $baseName = $namePool[array_rand($namePool)];
                $suffixes = ['', ' Putra', ' Saputra', ' Al-Farisi', ' Hasanah', ' Binti Ahmad', ' Bin Hamid', ''];
                $name     = $baseName . ($i % 5 === 0 ? $suffixes[array_rand($suffixes)] : '');

                do {
                    $nim = $bookYear . str_pad(rand(1, 99), 2, '0', STR_PAD_LEFT) . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT);
                } while (in_array($nim, $usedNims));
                $usedNims[] = $nim;

                $kaYudisium = $kaYudisiumOptions[array_rand($kaYudisiumOptions)];
                $ipkRanges  = [
                    'Dengan Pujian'     => [3.51, 4.00],
                    'Sangat Memuaskan'  => [3.01, 3.50],
                    'Memuaskan'         => [2.50, 3.00],
                ];
                [$ipkMin, $ipkMax] = $ipkRanges[$kaYudisium];
                $ipk = round($ipkMin + mt_rand() / mt_getrandmax() * ($ipkMax - $ipkMin), 2);

                $fakultasName = array_rand($fakultas);
                $prodiList    = $fakultas[$fakultasName];
                $prodi        = $prodiList[array_rand($prodiList)];

                $nominorCounter++;
                $nomor = (string) $nominorCounter;

                $birthYear = rand(1998, 2003);
                $birthDate = $bookYear . '-' . str_pad(rand(1, 12), 2, '0', STR_PAD_LEFT) . '-' . str_pad(rand(1, 28), 2, '0', STR_PAD_LEFT);
                $city      = ['Banda Aceh', 'Lhokseumawe', 'Sabang', 'Langsa', 'Subulussalam', 'Aceh Besar', 'Pidie', 'Bireuen'][rand(0, 7)];
                $ttl       = $city . ', ' . date('d F Y', strtotime($birthYear . '-' . rand(1, 12) . '-' . rand(1, 28)));

                $thesisTemplates = [
                    "Analisis {$prodi} dalam Perspektif Islam",
                    "Implementasi Sistem Manajemen {$prodi} di Era Digital",
                    "Studi Komparatif Metode Pembelajaran {$prodi}",
                    "Pengaruh Digitalisasi terhadap Perkembangan {$prodi}",
                    "Kontribusi {$prodi} dalam Pembangunan Masyarakat",
                    "Evaluasi Kurikulum {$prodi} di Perguruan Tinggi Islam",
                    "Peran {$prodi} dalam Penguatan Ekonomi Syariah",
                    "Tantangan dan Peluang {$prodi} di Era Globalisasi",
                ];
                $thesis = $thesisTemplates[array_rand($thesisTemplates)];

                $photoIndex = rand(1, 90);
                $genderPath = $isMale ? 'men' : 'women';
                $foto       = "https://randomuser.me/api/portraits/{$genderPath}/{$photoIndex}.jpg";

                $wisudawanRows[] = [
                    'nama'        => $name,
                    'nim'         => $nim,
                    'nomor'       => $nomor,
                    'ttl'         => $ttl,
                    'jenis_kelamin' => $gender,
                    'prodi'       => $prodi,
                    'fakultas'    => $fakultasName,
                    'ipk'         => $ipk,
                    'ka_yudisium' => $kaYudisium,
                    'judul_thesis' => $thesis,
                    'foto'        => $foto,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ];
            }
        }

        foreach (array_chunk($wisudawanRows, 50) as $chunk) {
            DB::connection($connection)->table('wisudawan')->insert($chunk);
        }

        $this->command->info('Seeded ' . count($wisudawanRows) . ' wisudawan into sqlite_api');
    }
}
