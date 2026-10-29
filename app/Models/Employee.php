<?php

namespace App\Models;

use Database\Factories\EmployeeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

class Employee extends Model
{
    /** @use HasFactory<EmployeeFactory> */
    use HasFactory;

    protected $table = 'pegawai';

    protected $fillable = [
        'nip',
        'nama',
        'jabatan',
    ];

    public function corrections(): HasMany
    {
        return $this->hasMany(AttendanceCorrection::class, 'pegawai_id');
    }

    /**
     * @return Collection<int, object>
     */
    public function getMonthlySubmissionStats(): Collection
    {
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        return AttendanceCorrection::query()
            ->where('perbaikan_detail.pegawai_id', $this->id)
            ->join('perbaikan', 'perbaikan_detail.perbaikan_id', '=', 'perbaikan.id')
            ->selectRaw('
                perbaikan.tahun,
                perbaikan.bulan,
                COUNT(perbaikan_detail.id) as total_pengajuan,
                SUM(CASE WHEN perbaikan_detail.status_presensi = "TM" THEN 1 ELSE 0 END) as tm_count,
                SUM(CASE WHEN perbaikan_detail.status_presensi = "HD" THEN 1 ELSE 0 END) as hd_count
            ')
            ->groupBy('perbaikan.tahun', 'perbaikan.bulan')
            ->orderByDesc('perbaikan.tahun')
            ->orderByDesc('perbaikan.bulan')
            ->get()
            ->map(function ($row) use ($months) {
                $row->nama_bulan = $months[(int) $row->bulan] ?? ('Bulan '.$row->bulan);
                $row->periode = $row->nama_bulan.' '.$row->tahun;

                return $row;
            });
    }
}
