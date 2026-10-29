<?php

namespace App\Filament\Resources\AttendanceReports;

use App\Filament\Resources\AttendanceReports\Pages\CreateAttendanceReport;
use App\Filament\Resources\AttendanceReports\Pages\EditAttendanceReport;
use App\Filament\Resources\AttendanceReports\Pages\ListAttendanceReports;
use App\Filament\Resources\AttendanceReports\Schemas\AttendanceReportForm;
use App\Filament\Resources\AttendanceReports\Tables\AttendanceReportsTable;
use App\Models\AttendanceReport;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class AttendanceReportResource extends Resource
{
    protected static ?string $model = AttendanceReport::class;

    protected static ?string $slug = 'perbaikan';

    protected static ?string $modelLabel = 'Laporan Perbaikan';

    protected static ?string $pluralModelLabel = 'Perbaikan';

    protected static ?string $navigationLabel = 'Perbaikan';

    protected static ?int $navigationSort = 3;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-check';

    public static function form(Schema $schema): Schema
    {
        return AttendanceReportForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AttendanceReportsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\CorrectionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAttendanceReports::route('/'),
            'create' => CreateAttendanceReport::route('/create'),
            'edit' => EditAttendanceReport::route('/{record}/edit'),
        ];
    }
}
