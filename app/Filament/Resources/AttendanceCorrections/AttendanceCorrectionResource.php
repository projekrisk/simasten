<?php

namespace App\Filament\Resources\AttendanceCorrections;

use App\Filament\Resources\AttendanceCorrections\Pages\CreateAttendanceCorrection;
use App\Filament\Resources\AttendanceCorrections\Pages\EditAttendanceCorrection;
use App\Filament\Resources\AttendanceCorrections\Pages\ListAttendanceCorrections;
use App\Filament\Resources\AttendanceCorrections\Schemas\AttendanceCorrectionForm;
use App\Models\AttendanceCorrection;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class AttendanceCorrectionResource extends Resource
{
    protected static ?string $model = AttendanceCorrection::class;

    protected static ?string $slug = 'detail-perbaikan';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $modelLabel = 'Pegawai & Kendala Presensi';

    protected static ?string $pluralModelLabel = 'Daftar Pegawai';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    public static function form(Schema $schema): Schema
    {
        return AttendanceCorrectionForm::configure($schema);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAttendanceCorrections::route('/'),
            'create' => CreateAttendanceCorrection::route('/create'),
            'edit' => EditAttendanceCorrection::route('/{record}/edit'),
        ];
    }
}
