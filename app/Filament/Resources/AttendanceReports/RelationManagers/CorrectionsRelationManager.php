<?php

namespace App\Filament\Resources\AttendanceReports\RelationManagers;

use App\Filament\Resources\AttendanceCorrections\AttendanceCorrectionResource;
use App\Filament\Resources\AttendanceCorrections\Schemas\AttendanceCorrectionForm;
use App\Models\AttendanceCorrection;
use App\Models\Employee;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class CorrectionsRelationManager extends RelationManager
{
    protected static string $relationship = 'corrections';

    protected static ?string $title = '';

    protected static ?string $modelLabel = 'Pegawai';

    protected static ?string $pluralModelLabel = 'Daftar Pegawai';

    public function form(Schema $schema): Schema
    {
        return AttendanceCorrectionForm::configure($schema);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitle(fn (AttendanceCorrection $record): string => $record->employee?->nama ?? 'Pegawai')
            ->defaultSort(function (Builder $query): Builder {
                return $query
                    ->orderBy('perbaikan_detail.tanggal', 'asc')
                    ->orderBy(
                        Employee::select('nama')->whereColumn('pegawai.id', 'perbaikan_detail.pegawai_id'),
                        'asc'
                    );
            })
            ->columns([
                TextColumn::make('employee.nama')
                    ->label('Nama Pegawai')
                    ->weight('bold')
                    ->description(fn (AttendanceCorrection $record): string => $record->employee?->nip ? 'NIP. '.$record->employee->nip : 'Non-PNS / Honorer')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('tanggal')
                    ->label('Tanggal Kendala')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('status_presensi')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'TM' => 'danger',
                        'HD' => 'warning',
                        default => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('jam_datang')
                    ->label('Jam Datang')
                    ->state(fn (AttendanceCorrection $record): string => ($record->jam_datang_asli ? substr($record->jam_datang_asli, 0, 5) : '-').' → '.($record->jam_datang_ubah ? substr($record->jam_datang_ubah, 0, 5) : '-'))
                    ->description('Asli → Ubah'),

                TextColumn::make('jam_pulang')
                    ->label('Jam Pulang')
                    ->state(fn (AttendanceCorrection $record): string => ($record->jam_pulang_asli ? substr($record->jam_pulang_asli, 0, 5) : '-').' → '.($record->jam_pulang_ubah ? substr($record->jam_pulang_ubah, 0, 5) : '-'))
                    ->description('Asli → Ubah'),
            ])
            ->filters([
                SelectFilter::make('status_presensi')
                    ->label('Filter Status')
                    ->options([
                        'TM' => 'TM - Tidak Absen Sama Sekali',
                        'HD' => 'HD - Hadir (Tdk Absen Pulang)',
                    ]),

                SelectFilter::make('pegawai_id')
                    ->label('Filter Pegawai')
                    ->options(fn () => Employee::orderBy('nama')->pluck('nama', 'id'))
                    ->searchable(),
            ])
            ->headerActions([
                Action::make('tambahPegawai')
                    ->label('Tambah Pegawai')
                    ->icon('heroicon-o-plus')
                    ->color('primary')
                    ->url(fn (): string => AttendanceCorrectionResource::getUrl('create', [
                        'perbaikan_id' => $this->getOwnerRecord()->id,
                    ])),
            ])
            ->recordActions([
                ActionGroup::make([
                    Action::make('ubahData')
                        ->label('Ubah Data Presensi')
                        ->icon('heroicon-o-pencil-square')
                        ->url(fn (AttendanceCorrection $record): string => AttendanceCorrectionResource::getUrl('edit', [
                            'record' => $record,
                        ])),
                    DeleteAction::make()
                        ->label('Hapus Pegawai')
                        ->icon('heroicon-o-trash'),
                ])
                    ->label('Aksi')
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->tooltip('Menu Aksi Pegawai'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('Hapus Terpilih'),
                ]),
            ]);
    }
}
