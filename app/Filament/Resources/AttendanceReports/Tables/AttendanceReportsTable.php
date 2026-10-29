<?php

namespace App\Filament\Resources\AttendanceReports\Tables;

use App\Models\AttendanceReport;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class AttendanceReportsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('periode')
                    ->label('Periode Laporan')
                    ->state(fn (AttendanceReport $record): string => $record->nama_bulan.' '.$record->tahun)
                    ->weight('bold')
                    ->sortable(['tahun', 'bulan']),

                TextColumn::make('nomor_surat_lengkap')
                    ->label('Nomor Surat Resmi')
                    ->searchable()
                    ->copyable()
                    ->fontFamily('mono')
                    ->default('-'),

                TextColumn::make('tanggal_surat')
                    ->label('Tanggal Surat')
                    ->date('d M Y')
                    ->sortable()
                    ->placeholder('-'),

                TextColumn::make('corrections_count')
                    ->counts('corrections')
                    ->label('Total Pegawai')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'final' => 'FINAL',
                        'draf' => 'DRAF',
                        default => strtoupper($state),
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'final' => 'success',
                        'draf' => 'warning',
                        default => 'gray',
                    }),

                TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('id', 'desc')
            ->filters([
                SelectFilter::make('tahun')
                    ->label('Filter Tahun')
                    ->options(function () {
                        $currentYear = (int) date('Y');
                        $years = [];
                        for ($y = $currentYear - 2; $y <= $currentYear + 2; $y++) {
                            $years[$y] = (string) $y;
                        }

                        return $years;
                    }),

                SelectFilter::make('bulan')
                    ->label('Filter Bulan')
                    ->options([
                        1 => '1 - Januari',
                        2 => '2 - Februari',
                        3 => '3 - Maret',
                        4 => '4 - April',
                        5 => '5 - Mei',
                        6 => '6 - Juni',
                        7 => '7 - Juli',
                        8 => '8 - Agustus',
                        9 => '9 - September',
                        10 => '10 - Oktober',
                        11 => '11 - November',
                        12 => '12 - Desember',
                    ]),

                SelectFilter::make('status')
                    ->label('Filter Status')
                    ->options([
                        'draf' => 'Draf (Belum Final)',
                        'final' => 'Final (Sudah Ditetapkan)',
                    ]),
            ])
            ->recordActions([
                ActionGroup::make([
                    Action::make('generatePdf')
                        ->label('Generate')
                        ->icon('heroicon-o-document-arrow-down')
                        ->color('primary')
                        ->modalHeading('Generate Berkas PDF Permohonan Resmi (2 Halaman A4)')
                        ->modalDescription('Masukkan nomor surat (angka saja) dan tanggal surat pengantar resmi. Sistem akan menyusun nomor surat lengkap, membuat berkas PDF 2 lembar, menyimpannya di public/uploads/pdf/, dan mengubah status laporan menjadi final.')
                        ->schema([
                            TextInput::make('nomor_surat')
                                ->label('Nomor Surat (Hanya Angka)')
                                ->placeholder('Contoh: 045')
                                ->default(fn (AttendanceReport $record) => $record->nomor_surat)
                                ->helperText('Format otomatis: 800/{nomor}/SMA.1-MLP/{tahun}')
                                ->required(),

                            DatePicker::make('tanggal_surat')
                                ->label('Tanggal Surat Pengesahan')
                                ->native(false)
                                ->displayFormat('d F Y')
                                ->prefixIcon('heroicon-o-calendar')
                                ->closeOnDateSelection()
                                ->default(fn (AttendanceReport $record) => $record->tanggal_surat ?? now())
                                ->required(),
                        ])
                        ->action(function (AttendanceReport $record, array $data): void {
                            $record->nomor_surat = trim($data['nomor_surat']);
                            $record->nomor_surat_lengkap = AttendanceReport::formatNomorSuratLengkap($data['nomor_surat'], $record->tahun);
                            $record->tanggal_surat = $data['tanggal_surat'];
                            $record->save();

                            $record->status = 'final';
                            $record->save();

                            Notification::make()
                                ->success()
                                ->title('Laporan Perbaikan Final')
                                ->body('Nomor surat dan tanggal pengesahan berhasil disimpan, berkas PDF siap langsung diunduh.')
                                ->actions([
                                    Action::make('download')
                                        ->label('Unduh Berkas')
                                        ->button()
                                        ->url(route('perbaikan.download-final', $record))
                                        ->openUrlInNewTab(),
                                ])
                                ->send();
                        }),

                    Action::make('unduhFinal')
                        ->label('Final PDF')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->color('success')
                        ->url(fn (AttendanceReport $record) => route('perbaikan.download-final', $record))
                        ->openUrlInNewTab()
                        ->visible(fn (AttendanceReport $record) => $record->status === 'final'),

                    Action::make('unduhDraf')
                        ->label('Draf PDF')
                        ->icon('heroicon-o-printer')
                        ->color('gray')
                        ->url(fn (AttendanceReport $record) => route('perbaikan.download-draft', $record))
                        ->openUrlInNewTab(),

                    EditAction::make()
                        ->label('Ubah Laporan')
                        ->icon('heroicon-o-pencil-square'),

                    DeleteAction::make()
                        ->label('Hapus Laporan')
                        ->icon('heroicon-o-trash'),
                ])
                    ->label('Aksi')
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->tooltip('Pilihan Menu Aksi'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('Hapus Terpilih'),
                ]),
            ]);
    }
}
