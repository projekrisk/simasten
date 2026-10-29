<?php

namespace App\Filament\Resources\AttendanceReports\Pages;

use App\Filament\Resources\AttendanceReports\AttendanceReportResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\Width;

class EditAttendanceReport extends EditRecord
{
    protected static string $resource = AttendanceReportResource::class;

    protected static ?string $title = 'Ubah Perbaikan';

    public function getMaxContentWidth(): Width|string|null
    {
        return Width::Full;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('unduhDraf')
                ->label('Draf')
                ->icon('heroicon-o-printer')
                ->color('gray')
                ->url(fn () => route('perbaikan.download-draft', $this->record))
                ->openUrlInNewTab(),

            Action::make('unduhFinal')
                ->label('Final')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->url(fn () => route('perbaikan.download-final', $this->record))
                ->openUrlInNewTab()
                ->visible(fn () => $this->record->status === 'final' || ! empty($this->record->pdf_file_path)),

            DeleteAction::make()->label('Hapus'),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
