<?php

namespace App\Filament\Resources\Prequalifications;

use App\Filament\Resources\Prequalifications\Pages\CreatePrequalification;
use App\Filament\Resources\Prequalifications\Pages\EditPrequalification;
use App\Filament\Resources\Prequalifications\Pages\ListPrequalifications;
use App\Filament\Resources\Prequalifications\Pages\ViewPrequalification;
use App\Filament\Resources\Prequalifications\Schemas\PrequalificationForm;
use App\Filament\Resources\Prequalifications\Schemas\PrequalificationInfolist;
use App\Filament\Resources\Prequalifications\Tables\PrequalificationsTable;
use App\Models\Prequalification;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PrequalificationResource extends Resource
{
    protected static ?string $model = Prequalification::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static ?string $navigationLabel = 'Prequalifications';
    protected static string|UnitEnum|null $navigationGroup = 'Operations';
    protected static ?int $navigationSort = 20;

    protected static ?string $recordTitleAttribute = 'company_name';

    public static function form(Schema $schema): Schema
    {
        return PrequalificationForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PrequalificationInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PrequalificationsTable::configure($table);
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
            'index' => ListPrequalifications::route('/'),
            'create' => CreatePrequalification::route('/create'),
            'view' => ViewPrequalification::route('/{record}'),
            'edit' => EditPrequalification::route('/{record}/edit'),
        ];
    }
}
