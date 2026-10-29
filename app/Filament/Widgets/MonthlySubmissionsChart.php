<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class MonthlySubmissionsChart extends ChartWidget
{
    protected ?string $heading = 'Ajuan Perbaikan';

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $currentYear = (int) now()->format('Y');

        $monthNames = [
            'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
            'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des',
        ];

        $dataPerBulan = DB::table('perbaikan_detail')
            ->join('perbaikan', 'perbaikan_detail.perbaikan_id', '=', 'perbaikan.id')
            ->where('perbaikan.tahun', $currentYear)
            ->select('perbaikan.bulan', DB::raw('count(perbaikan_detail.id) as total'))
            ->groupBy('perbaikan.bulan')
            ->pluck('total', 'bulan')
            ->all();

        $dataTm = DB::table('perbaikan_detail')
            ->join('perbaikan', 'perbaikan_detail.perbaikan_id', '=', 'perbaikan.id')
            ->where('perbaikan.tahun', $currentYear)
            ->where('perbaikan_detail.status_presensi', 'TM')
            ->select('perbaikan.bulan', DB::raw('count(perbaikan_detail.id) as total'))
            ->groupBy('perbaikan.bulan')
            ->pluck('total', 'bulan')
            ->all();

        $dataHd = DB::table('perbaikan_detail')
            ->join('perbaikan', 'perbaikan_detail.perbaikan_id', '=', 'perbaikan.id')
            ->where('perbaikan.tahun', $currentYear)
            ->where('perbaikan_detail.status_presensi', 'HD')
            ->select('perbaikan.bulan', DB::raw('count(perbaikan_detail.id) as total'))
            ->groupBy('perbaikan.bulan')
            ->pluck('total', 'bulan')
            ->all();

        $totals = [];
        $tms = [];
        $hds = [];

        for ($m = 1; $m <= 12; $m++) {
            $totals[] = $dataPerBulan[$m] ?? 0;
            $tms[] = $dataTm[$m] ?? 0;
            $hds[] = $dataHd[$m] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Ajuan ('.$currentYear.')',
                    'data' => $totals,
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.12)',
                    'fill' => true,
                    'tension' => 0.4,
                    'pointRadius' => 4,
                    'pointHoverRadius' => 6,
                    'pointBackgroundColor' => '#3b82f6',
                    'pointBorderColor' => '#ffffff',
                    'pointBorderWidth' => 1.5,
                    'borderWidth' => 2.5,
                ],
                [
                    'label' => 'Status TM (Tidak Presensi)',
                    'data' => $tms,
                    'borderColor' => '#ef4444',
                    'backgroundColor' => 'rgba(239, 68, 68, 0.08)',
                    'fill' => false,
                    'tension' => 0.4,
                    'pointRadius' => 4,
                    'pointHoverRadius' => 6,
                    'pointBackgroundColor' => '#ef4444',
                    'pointBorderColor' => '#ffffff',
                    'pointBorderWidth' => 1.5,
                    'borderWidth' => 2,
                ],
                [
                    'label' => 'Status HD (Hadir - Tdk Pulang)',
                    'data' => $hds,
                    'borderColor' => '#f59e0b',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.08)',
                    'fill' => false,
                    'tension' => 0.4,
                    'pointRadius' => 4,
                    'pointHoverRadius' => 6,
                    'pointBackgroundColor' => '#f59e0b',
                    'pointBorderColor' => '#ffffff',
                    'pointBorderWidth' => 1.5,
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $monthNames,
        ];
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'top',
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'grace' => '15%',
                    'ticks' => [
                        'precision' => 0,
                        'stepSize' => 1,
                    ],
                ],
                'x' => [
                    'grid' => [
                        'display' => false,
                    ],
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
