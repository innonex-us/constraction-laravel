<?php

namespace App\Filament\Resources\Badges\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;

class BadgesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')->circular()->label('Logo')->size(36),
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('url')->url(fn($record) => $record->url, true)->wrap(),
                TextColumn::make('order')->sortable(),
                TextColumn::make('is_active')->badge()->formatStateUsing(fn($s) => $s ? 'Active' : 'Hidden'),
            ])
            ->filters([
                TernaryFilter::make('is_active')->label('Active')
                    ->placeholder('All')->trueLabel('Active')->falseLabel('Hidden'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

