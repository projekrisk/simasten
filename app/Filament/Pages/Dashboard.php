<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\MonthlySubmissionsChart;
use App\Filament\Widgets\StatsOverview;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $title = 'Dasbor Utama';

    protected static ?string $navigationLabel = 'Dasbor';

    protected static ?string $slug = 'dasbor';

    protected static ?int $navigationSort = 1;

    public function getWidgets(): array
    {
        return [
            StatsOverview::class,
            MonthlySubmissionsChart::class,
        ];
    }
}
