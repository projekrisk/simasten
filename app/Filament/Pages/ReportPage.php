<?php

namespace App\Filament\Pages;

use App\Models\AttendanceCorrection;
use App\Models\AttendanceReport;
use App\Models\Employee;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Pages\Page;
use Filament\Support\Enums\Width;

class ReportPage extends Page
{
    protected static string|null|\BackedEnum $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $slug = 'laporan';

    protected static ?string $title = 'Laporan';

    protected static ?string $navigationLabel = 'Laporan';

    protected static ?int $navigationSort = 4;

    protected string $view = 'filament.pages.report-page';

    public function getMaxContentWidth(): Width|string|null
    {
        return Width::SevenExtraLarge;
    }

    public int|string|null $selectedYear = null;

    public function mount(): void
    {
        $this->selectedYear = (int) date('Y');
    }

    public function updatedSelectedYear(): void
    {
        if (filled($this->selectedYear) && is_numeric($this->selectedYear)) {
            $this->selectedYear = (int) $this->selectedYear;
        }
    }

    public function downloadRankingPdf()
    {
        $year = (int) ($this->selectedYear ?: date('Y'));
        $employees = $this->getTopEmployees(null);
        $setting = Setting::current();

        $pdf = Pdf::loadView('pdf.peringkat-pegawai', [
            'year' => $year,
            'employees' => $employees,
            'setting' => $setting,
        ]);

        $pdf->setPaper('a4', 'portrait')
            ->setOption('isRemoteEnabled', true)
            ->setOption('isHtml5ParserEnabled', true);

        return response()->streamDownload(
            fn () => print ($pdf->output()),
            "Laporan Peringkat Pegawai Tahun {$year}.pdf",
            ['Content-Type' => 'application/pdf']
        );
    }

    public function getHistoryStats(): array
    {
        $year = (int) ($this->selectedYear ?: date('Y'));

        $monthNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        $reports = AttendanceReport::where('tahun', $year)
            ->withCount([
                'corrections as total_corrections',
                'corrections as tm_count' => fn ($q) => $q->where('status_presensi', 'TM'),
                'corrections as hd_count' => fn ($q) => $q->where('status_presensi', 'HD'),
            ])
            ->get()
            ->keyBy('bulan');

        $result = [];
        for ($m = 1; $m <= 12; $m++) {
            $report = $reports->get($m);
            $result[] = [
                'bulan' => $m,
                'nama_bulan' => $monthNames[$m],
                'tahun' => $year,
                'total' => $report ? $report->total_corrections : 0,
                'tm' => $report ? $report->tm_count : 0,
                'hd' => $report ? $report->hd_count : 0,
                'status' => $report ? $report->status : null,
                'nomor_surat' => $report ? $report->nomor_surat_lengkap : null,
                'report_id' => $report ? $report->id : null,
            ];
        }

        return $result;
    }

    public function getTopEmployees(?int $limit = 10): array
    {
        $year = (int) ($this->selectedYear ?: date('Y'));

        $query = Employee::query()
            ->whereHas('corrections.report', fn ($q) => $q->where('tahun', $year))
            ->withCount([
                'corrections' => fn ($q) => $q->whereHas('report', fn ($r) => $r->where('tahun', $year)),
                'corrections as tm_count' => fn ($q) => $q->where('status_presensi', 'TM')->whereHas('report', fn ($r) => $r->where('tahun', $year)),
                'corrections as hd_count' => fn ($q) => $q->where('status_presensi', 'HD')->whereHas('report', fn ($r) => $r->where('tahun', $year)),
            ])
            ->orderByDesc('corrections_count');

        if ($limit !== null) {
            $query->take($limit);
        }

        return $query->get()->toArray();
    }

    public function getStatusComparison(): array
    {
        $year = (int) ($this->selectedYear ?: date('Y'));

        $tm = AttendanceCorrection::where('status_presensi', 'TM')
            ->whereHas('report', fn ($q) => $q->where('tahun', $year))
            ->count();

        $hd = AttendanceCorrection::where('status_presensi', 'HD')
            ->whereHas('report', fn ($q) => $q->where('tahun', $year))
            ->count();

        $total = $tm + $hd;
        $tmPct = $total > 0 ? round(($tm / $total) * 100, 1) : 0;
        $hdPct = $total > 0 ? round(($hd / $total) * 100, 1) : 0;

        return [
            'total' => $total,
            'tm' => $tm,
            'hd' => $hd,
            'tm_pct' => $tmPct,
            'hd_pct' => $hdPct,
        ];
    }
}
