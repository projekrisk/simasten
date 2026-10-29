<?php

namespace App\Filament\Resources\AttendanceCorrections\Schemas;

use App\Models\AttendanceCorrection;
use App\Models\AttendanceReport;
use App\Models\Employee;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AttendanceCorrectionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('perbaikan_id')
                    ->default(fn () => request()->query('perbaikan_id'))
                    ->required(),

                Section::make('Informasi Pegawai')
                    ->icon('heroicon-o-identification')
                    ->columnSpanFull()
                    ->schema([
                        Placeholder::make('info_laporan')
                            ->label('Rujukan Laporan Perbaikan')
                            ->columnSpanFull()
                            ->content(function ($record): string {
                                $reportId = $record?->perbaikan_id ?? request()->query('perbaikan_id');
                                $report = $reportId ? AttendanceReport::find($reportId) : null;
                                if (! $report) {
                                    return 'Laporan Perbaikan Presensi';
                                }

                                return "Bulan {$report->nama_bulan} {$report->tahun}".($report->nomor_surat_lengkap ? " — {$report->nomor_surat_lengkap}" : ' (Status: '.strtoupper($report->status).')');
                            }),

                        Select::make('pegawai_id')
                            ->label('Nama Lengkap Pegawai')
                            ->options(Employee::orderBy('nama')->pluck('nama', 'id'))
                            ->searchable()
                            ->preload()
                            ->prefixIcon('heroicon-o-user')
                            ->placeholder('Ketik atau pilih nama pegawai...')
                            ->required()
                            ->columnSpanFull(),

                        Placeholder::make('info_pengajuan_pegawai')
                            ->label('Riwayat Pengajuan Pegawai')
                            ->columnSpanFull()
                            ->visible(fn ($record) => $record?->pegawai_id !== null)
                            ->content(function ($record): string {
                                if (! $record?->pegawai_id) {
                                    return '';
                                }
                                $reportId = $record->perbaikan_id ?? request()->query('perbaikan_id');
                                $report = $reportId ? AttendanceReport::find($reportId) : null;
                                $monthName = $report ? "bulan {$report->nama_bulan} {$report->tahun}" : 'laporan ini';

                                $countMonth = AttendanceCorrection::where('pegawai_id', $record->pegawai_id)
                                    ->when($report, fn ($q) => $q->where('perbaikan_id', $report->id))
                                    ->count();

                                $countTotal = AttendanceCorrection::where('pegawai_id', $record->pegawai_id)->count();

                                return "Pegawai ini mengajukan {$countMonth} kali perbaikan presensi pada {$monthName} (Total keseluruhan: {$countTotal} kali).";
                            }),

                        Grid::make(2)
                            ->columnSpanFull()
                            ->schema([
                                DatePicker::make('tanggal')
                                    ->label('Tanggal Kendala Presensi')
                                    ->native(false)
                                    ->displayFormat('d F Y')
                                    ->prefixIcon('heroicon-o-calendar')
                                    ->closeOnDateSelection()
                                    ->default(now())
                                    ->required(),

                                Select::make('status_presensi')
                                    ->label('Status Kendala Presensi')
                                    ->options([
                                        'TM' => 'TM — Tidak Absen Sama Sekali',
                                        'HD' => 'HD — Hadir (Tidak melakukan absen pulang)',
                                    ])
                                    ->prefixIcon('heroicon-o-exclamation-triangle')
                                    ->default('TM')
                                    ->required(),
                            ]),

                        Grid::make(4)
                            ->columnSpanFull()
                            ->schema([
                                TimePicker::make('jam_datang_asli')
                                    ->label('Jam Datang Asli')
                                    ->native(false)
                                    ->seconds(false)
                                    ->prefixIcon('heroicon-o-clock')
                                    ->placeholder('07:15')
                                    ->helperText('Kosongkan bila status TM'),

                                TimePicker::make('jam_datang_ubah')
                                    ->label('Jam Datang Ubah')
                                    ->native(false)
                                    ->seconds(false)
                                    ->prefixIcon('heroicon-o-check-circle')
                                    ->default('07:00')
                                    ->required()
                                    ->helperText('Jam kedatangan resmi'),

                                TimePicker::make('jam_pulang_asli')
                                    ->label('Jam Pulang Asli')
                                    ->native(false)
                                    ->seconds(false)
                                    ->prefixIcon('heroicon-o-clock')
                                    ->placeholder('15:35')
                                    ->helperText('Kosongkan bila tdk terekam'),

                                TimePicker::make('jam_pulang_ubah')
                                    ->label('Jam Pulang Ubah')
                                    ->native(false)
                                    ->seconds(false)
                                    ->prefixIcon('heroicon-o-check-circle')
                                    ->default('15:30')
                                    ->required()
                                    ->helperText('Jam kepulangan resmi'),
                            ]),

                        Grid::make(2)
                            ->columnSpanFull()
                            ->schema([
                                FileUpload::make('foto_datang')
                                    ->label('Bukti Foto Selfie Kedatangan (Rasio 3:4)')
                                    ->disk('public_uploads')
                                    ->directory('presensi')
                                    ->visibility('public')
                                    ->image()
                                    ->imageEditor()
                                    ->openable()
                                    ->downloadable()
                                    ->imagePreviewHeight('240')
                                    ->helperText('Foto selfie berseragam di lokasi sekolah saat kedatangan'),

                                FileUpload::make('foto_pulang')
                                    ->label('Bukti Foto Selfie Kepulangan (Rasio 3:4)')
                                    ->disk('public_uploads')
                                    ->directory('presensi')
                                    ->visibility('public')
                                    ->image()
                                    ->imageEditor()
                                    ->openable()
                                    ->downloadable()
                                    ->imagePreviewHeight('240')
                                    ->helperText('Foto selfie berseragam di lokasi sekolah saat kepulangan'),
                            ]),
                    ]),
            ]);
    }
}
