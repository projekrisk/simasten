<?php

namespace App\Filament\Resources\AttendanceCorrections\Pages;

use App\Filament\Resources\AttendanceCorrections\AttendanceCorrectionResource;
use App\Filament\Resources\AttendanceReports\AttendanceReportResource;
use App\Models\AttendanceCorrection;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\Width;

class EditAttendanceCorrection extends EditRecord
{
    protected static string $resource = AttendanceCorrectionResource::class;

    protected static ?string $title = 'Ubah Data Presensi Pegawai';

    public function getMaxContentWidth(): Width|string|null
    {
        return Width::Full;
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->label('Hapus Pegawai')
                ->successRedirectUrl(fn (AttendanceCorrection $record): string => AttendanceReportResource::getUrl('edit', ['record' => $record->perbaikan_id])),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return AttendanceReportResource::getUrl('edit', ['record' => $this->getRecord()->perbaikan_id]);
    }
}
