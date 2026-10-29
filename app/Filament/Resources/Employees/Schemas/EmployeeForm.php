<?php

namespace App\Filament\Resources\Employees\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;

class EmployeeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Pegawai')
                    ->icon('heroicon-o-user')
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('nama')
                            ->label('Nama Lengkap & Gelar')
                            ->placeholder('Contoh: Drs. Budi Santoso, M.Pd.')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('nip')
                            ->label('NIP (Nomor Induk Pegawai)')
                            ->placeholder('18 digit angka (contoh: 198501152010011002)')
                            ->helperText('Kosongkan bila pegawai berstatus Non-PNS / Honorer.')
                            ->maxLength(30),
                    ])->columns(2),

                Section::make('Riwayat')
                    ->icon('heroicon-o-calendar-days')
                    ->columnSpanFull()
                    ->visible(fn ($record) => $record !== null)
                    ->schema([
                        View::make('filament.forms.components.employee-monthly-submissions')
                            ->columnSpanFull()
                            ->viewData(fn ($record) => [
                                'employee' => $record,
                                'stats' => $record ? $record->getMonthlySubmissionStats() : collect(),
                                'totalAll' => $record ? $record->corrections()->count() : 0,
                                'totalTm' => $record ? $record->corrections()->where('status_presensi', 'TM')->count() : 0,
                                'totalHd' => $record ? $record->corrections()->where('status_presensi', 'HD')->count() : 0,
                            ]),
                    ]),
            ]);
    }
}
