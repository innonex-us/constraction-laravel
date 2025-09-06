<?php

namespace App\Filament\Resources\SafetyRecords;

use App\Filament\Resources\SafetyRecords\Pages\CreateSafetyRecord;
use App\Filament\Resources\SafetyRecords\Pages\EditSafetyRecord;
use App\Filament\Resources\SafetyRecords\Pages\ListSafetyRecords;
use App\Filament\Resources\SafetyRecords\Pages\ViewSafetyRecord;
use App\Filament\Resources\SafetyRecords\Schemas\SafetyRecordForm;
use App\Filament\Resources\SafetyRecords\Schemas\SafetyRecordInfolist;
use App\Filament\Resources\SafetyRecords\Tables\SafetyRecordsTable;
use App\Models\SafetyRecord;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SafetyRecordResource extends Resource
{
    protected static ?string $model = SafetyRecord::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'year';

    public static function form(Schema $schema): Schema
    {
        return SafetyRecordForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SafetyRecordInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SafetyRecordsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSafetyRecords::route('/'),
            'create' => CreateSafetyRecord::route('/create'),
            'view' => ViewSafetyRecord::route('/{record}'),
            'edit' => EditSafetyRecord::route('/{record}/edit'),
        ];
    }
}
