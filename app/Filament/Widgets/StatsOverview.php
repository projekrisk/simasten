<?php

namespace App\Filament\Widgets;

use App\Models\AttendanceCorrection;
use App\Models\AttendanceReport;
use App\Models\Employee;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $currentMonth = (int) now()->format('n');
        $currentYear = (int) now()->format('Y');

        $currentReport = AttendanceReport::where('bulan', $currentMonth)
            ->where('tahun', $currentYear)
            ->first();

        $monthlyCount = $currentReport ? $currentReport->corrections()->count() : 0;

        $tmCount = AttendanceCorrection::where('status_presensi', 'TM')
            ->whereHas('report', fn ($q) => $q->where('bulan', $currentMonth)->where('tahun', $currentYear))
            ->count();

        $hdCount = AttendanceCorrection::where('status_presensi', 'HD')
            ->whereHas('report', fn ($q) => $q->where('bulan', $currentMonth)->where('tahun', $currentYear))
            ->count();

        $totalEmployees = Employee::count();

        $namaBulan = Carbon::now()->translatedFormat('F Y');

        return [
            Stat::make(''.$namaBulan.'', $monthlyCount.' Ajuan')
                ->description('Perbaikan')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('primary'),

            Stat::make('Status TM', $tmCount.' Kasus')
                ->description('Telat masuk')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger'),

            Stat::make('Status HD', $hdCount.' Kasus')
                ->description('Pulang cepat')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Total Pegawai', $totalEmployees.' Orang')
                ->description('GTK & Staf')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),
        ];
    }
}
