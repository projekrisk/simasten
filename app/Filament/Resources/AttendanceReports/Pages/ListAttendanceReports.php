<?php

namespace App\Filament\Resources\AttendanceReports\Pages;

use App\Filament\Resources\AttendanceReports\AttendanceReportResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAttendanceReports extends ListRecords
{
    protected static string $resource = AttendanceReportResource::class;

    protected static ?string $title = 'Perbaikan Presensi';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Perbaikan')
                ->icon('heroicon-o-plus'),
        ];
    }
}
