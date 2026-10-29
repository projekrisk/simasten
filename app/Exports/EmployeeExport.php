<?php

namespace App\Exports;

use App\Models\Employee;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EmployeeExport implements FromCollection, ShouldAutoSize, WithHeadings, WithMapping
{
    public function collection(): Collection
    {
        return Employee::orderBy('nama')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'NIP',
            'Nama Lengkap',
        ];
    }

    /**
     * @param  Employee  $employee
     */
    public function map($employee): array
    {
        return [
            $employee->id,
            $employee->nip ? "'".$employee->nip : '-',
            $employee->nama,
        ];
    }
}
