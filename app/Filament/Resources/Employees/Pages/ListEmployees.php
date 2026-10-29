<?php

namespace App\Filament\Resources\Employees\Pages;

use App\Exports\EmployeeExport;
use App\Filament\Resources\Employees\EmployeeResource;
use App\Imports\EmployeeImport;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Facades\Excel;

class ListEmployees extends ListRecords
{
    protected static string $resource = EmployeeResource::class;

    protected static ?string $title = 'Pegawai';

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Pegawai')
                ->icon('heroicon-o-plus'),

            Action::make('importExcel')
                ->label('Impor')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('success')
                ->modalHeading('Impor Data Pegawai dari Berkas Excel (.xlsx)')
                ->modalDescription('Unggah berkas spreadsheet dengan kolom: NIP, Nama Lengkap')
                ->schema([
                    FileUpload::make('berkas')
                        ->label('Berkas Excel (.xlsx / .xls / .csv)')
                        ->disk('public_uploads')
                        ->directory('imports')
                        ->acceptedFileTypes([
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'application/vnd.ms-excel',
                            'text/csv',
                            'application/csv',
                        ])
                        ->required(),
                ])
                ->action(function (array $data): void {
                    $filePath = public_path('uploads/'.$data['berkas']);
                    if (! file_exists($filePath)) {
                        Notification::make()
                            ->danger()
                            ->title('Gagal Mengunggah')
                            ->body('Berkas tidak ditemukan pada server.')
                            ->send();

                        return;
                    }

                    $import = new EmployeeImport;
                    Excel::import($import, $filePath);

                    // Clean up temporary file
                    @unlink($filePath);

                    Notification::make()
                        ->success()
                        ->title('Impor Berhasil')
                        ->body("Sebanyak {$import->importedCount} data pegawai berhasil disimpan.")
                        ->send();
                }),

            Action::make('exportExcel')
                ->label('Ekspor')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('gray')
                ->action(fn () => Excel::download(new EmployeeExport, 'data-pegawai-simasten-'.date('Ymd-His').'.xlsx')),
        ];
    }
}
