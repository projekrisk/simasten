<?php

namespace App\Models;

use Carbon\Carbon;
use Database\Factories\AttendanceReportFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AttendanceReport extends Model
{
    /** @use HasFactory<AttendanceReportFactory> */
    use HasFactory;

    protected $table = 'perbaikan';

    protected $fillable = [
        'bulan',
        'tahun',
        'nomor_surat',
        'nomor_surat_lengkap',
        'tanggal_surat',
        'status',
        'pdf_file_path',
    ];

    protected function casts(): array
    {
        return [
            'bulan' => 'integer',
            'tahun' => 'integer',
            'tanggal_surat' => 'date',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (AttendanceReport $report) {
            if (! empty($report->nomor_surat) && ! empty($report->tahun)) {
                $report->nomor_surat_lengkap = static::formatNomorSuratLengkap($report->nomor_surat, $report->tahun);
            }
        });
    }

    public function corrections(): HasMany
    {
        return $this->hasMany(AttendanceCorrection::class, 'perbaikan_id');
    }

    public static function formatNomorSuratLengkap(string $nomorSurat, int|string $tahun): string
    {
        $nomorSurat = trim($nomorSurat);

        return "800/{$nomorSurat}/SMA.1-MLP/{$tahun}";
    }

    public function getNamaBulanAttribute(): string
    {
        $months = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        return $months[$this->bulan] ?? Carbon::create()->month($this->bulan)->translatedFormat('F');
    }
}
