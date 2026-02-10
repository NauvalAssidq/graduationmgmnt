<?php

namespace App\Imports;

use App\Models\Wisudawan;
use App\Models\BukuWisuda;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use Illuminate\Support\Str;

class WisudawanImport extends DefaultValueBinder implements ToCollection, WithHeadingRow, WithCustomValueBinder
{
    protected string $gelombang;
    protected string $tahun;

    public function __construct(string $gelombang, string $tahun)
    {
        $this->gelombang = $gelombang;
        $this->tahun = $tahun;
    }

    public function bindValue(Cell $cell, $value)
    {
        if ($cell->isFormula()) {
            $cached = $cell->getOldCalculatedValue();
            if ($cached !== null) {
                $cell->setValueExplicit($cached, DataType::TYPE_STRING);
                return true;
            }
        }
        return parent::bindValue($cell, $value);
    }
    public function headingRow(): int
    {
        return 1;
    }

    public function startRow(): int
    {
        return 4;
    }

    public function collection(Collection $rows)
    {
        $booksCache = [];

        foreach ($rows as $row) {
            $nim = isset($row['nim']) ? trim($row['nim']) : null;

            if (empty($nim) || !is_numeric($nim) || strlen($nim) < 5) {
                continue;
            }

            $nama      = $row['nama_lulusan'] ?? '-';
            $gender    = $row['jk'] ?? 'L';

            $tempatLahir  = $row['tempat_tanggal_lahir'] ?? '';
            $tanggalLahir = $row['col_9'] ?? ($row[9] ?? '');
            $ttl = trim($tempatLahir) . ($tanggalLahir ? ', ' . trim($tanggalLahir) : '');

            $fakultasRaw = $row['fakultas_jps'] ?? ($row['fakultas'] ?? '-');
            $parts       = explode('-', $fakultasRaw);
            $fakultas    = trim($parts[0] ?? '-');
            $prodiRaw   = $row['program_studi'] ?? '-';
            $prodiParts = explode('-', $prodiRaw);
            $prodi      = count($prodiParts) > 1 ? trim($prodiParts[1]) : trim($prodiParts[0]);

            $ipkRaw = $row['ipk'] ?? 0.0;
            $ipk    = is_numeric($ipkRaw) ? floatval($ipkRaw) : 0.0;

            $yudisium = $row['kategori_yudisium'] ?? '-';
            $judul    = $row['judul_tugas_akhir_skripsitesisdisertasi'] ?? '-';
            $nomor    = $row['nomor_ijazah_uinar'] ?? ($row['nomor_sk_yudisium'] ?? '-');

            $gelombang = $this->gelombang;
            $tahun     = $this->tahun;

            $bookKey = $gelombang . '-' . $tahun;

            if (!isset($booksCache[$bookKey])) {
                $book = BukuWisuda::firstOrCreate(
                    ['gelombang' => $gelombang, 'tahun' => $tahun],
                    [
                        'nama_buku'      => "Wisuda Gelombang $gelombang Tahun $tahun",
                        'tanggal_terbit' => now(),
                        'status'         => 'Draft',
                        'slug'           => Str::slug("Wisuda Gelombang $gelombang Tahun $tahun"),
                    ]
                );
                $booksCache[$bookKey] = $book->id;
            }

            $bukuId = $booksCache[$bookKey];

            Wisudawan::updateOrCreate(
                ['nim' => $nim],
                [
                    'id_buku'       => $bukuId,
                    'nama'          => $nama,
                    'nomor'         => $nomor,
                    'ttl'           => $ttl,
                    'jenis_kelamin' => $gender,
                    'prodi'         => $prodi,
                    'fakultas'      => $fakultas,
                    'ipk'           => $ipk,
                    'ka_yudisium'   => $yudisium,
                    'judul_thesis'  => $judul,
                    'foto'          => '',
                ]
            );
        }
    }
}
