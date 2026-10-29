<?php

namespace App\Filament\Resources\AttendanceReports\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AttendanceReportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Laporan')
                    ->icon('heroicon-o-document-text')
                    ->columnSpanFull()
                    ->schema([
                        Grid::make(3)
                            ->columnSpanFull()
                            ->schema([
                                Select::make('bulan')
                                    ->label('Periode Bulan')
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
                                    ])
                                    ->default((int) now()->format('n'))
                                    ->required(),

                                TextInput::make('tahun')
                                    ->label('Tahun Periode')
                                    ->numeric()
                                    ->default((int) now()->format('Y'))
                                    ->required(),

                                Select::make('status')
                                    ->label('Status Laporan')
                                    ->options([
                                        'draf' => 'Draf (Belum Final)',
                                        'final' => 'Final (Sudah Ditetapkan)',
                                    ])
                                    ->default('draf')
                                    ->required(),
                            ]),

                        Grid::make(2)
                            ->columnSpanFull()
                            ->schema([
                                TextInput::make('nomor_surat')
                                    ->label('Nomor Surat (Angka Saja)')
                                    ->placeholder('Contoh: 045')
                                    ->helperText('Format otomatis: 800/{nomor}/SMA.1-MLP/{tahun}')
                                    ->maxLength(50),

                                DatePicker::make('tanggal_surat')
                                    ->label('Tanggal Surat Resmi')
                                    ->native(false)
                                    ->displayFormat('d F Y')
                                    ->prefixIcon('heroicon-o-calendar')
                                    ->closeOnDateSelection()
                                    ->default(now())
                                    ->required(),
                            ]),
                    ]),
            ]);
    }
}
