<?php

namespace App\Filament\Resources\AttendanceCorrections\Pages;

use App\Filament\Resources\AttendanceCorrections\AttendanceCorrectionResource;
use App\Filament\Resources\AttendanceReports\AttendanceReportResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Width;

class CreateAttendanceCorrection extends CreateRecord
{
    protected static string $resource = AttendanceCorrectionResource::class;

    protected static ?string $title = 'Tambah Pegawai';

    public function getMaxContentWidth(): Width|string|null
    {
        return Width::Full;
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (empty($data['perbaikan_id'])) {
            $data['perbaikan_id'] = request()->query('perbaikan_id');
        }

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        $perbaikanId = $this->getRecord()->perbaikan_id ?? request()->query('perbaikan_id');

        if ($perbaikanId) {
            return AttendanceReportResource::getUrl('edit', ['record' => $perbaikanId]);
        }

        return AttendanceReportResource::getUrl('index');
    }
}
