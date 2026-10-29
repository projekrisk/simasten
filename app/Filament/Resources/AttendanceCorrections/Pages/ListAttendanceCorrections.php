<?php

namespace App\Filament\Resources\AttendanceCorrections\Pages;

use App\Filament\Resources\AttendanceCorrections\AttendanceCorrectionResource;
use App\Filament\Resources\AttendanceReports\AttendanceReportResource;
use Filament\Resources\Pages\ListRecords;

class ListAttendanceCorrections extends ListRecords
{
    protected static string $resource = AttendanceCorrectionResource::class;

    public function mount(): void
    {
        $this->redirect(AttendanceReportResource::getUrl('index'));
    }
}
