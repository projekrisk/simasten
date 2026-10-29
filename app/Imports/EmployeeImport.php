<?php

namespace App\Imports;

use App\Models\Employee;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EmployeeImport implements ToCollection, WithHeadingRow
{
    public int $importedCount = 0;

    public function collection(Collection $rows): void
    {
        foreach ($rows as $row) {
            $nama = $row['nama_lengkap'] ?? $row['nama'] ?? $row['nama_pegawai'] ?? null;
            if (! $nama) {
                continue;
            }

            $nip = $row['nip'] ?? null;
            if ($nip !== null) {
                $nip = trim((string) $nip, "' \t\n\r\0\x0B");
                if ($nip === '' || $nip === '-') {
                    $nip = null;
                }
            }

            $jabatan = $row['jabatan'] ?? null;

            if ($nip) {
                Employee::updateOrCreate(
                    ['nip' => $nip],
                    [
                        'nama' => trim((string) $nama),
                        'jabatan' => $jabatan ? trim((string) $jabatan) : null,
                    ]
                );
            } else {
                Employee::updateOrCreate(
                    ['nama' => trim((string) $nama), 'nip' => null],
                    [
                        'jabatan' => $jabatan ? trim((string) $jabatan) : null,
                    ]
                );
            }

            $this->importedCount++;
        }
    }
}
